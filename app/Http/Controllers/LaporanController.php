<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $startOfMonth = now()->copy()->startOfMonth();
        $endOfMonth = now()->copy()->endOfMonth();

        $previousStart = now()->copy()->subMonthNoOverflow()->startOfMonth();
        $previousEnd = now()->copy()->subMonthNoOverflow()->endOfMonth();

        $monthlyQuery = Transaksi::query()
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth]);

        $previousMonthQuery = Transaksi::query()
            ->whereBetween('tanggal_transaksi', [$previousStart, $previousEnd]);

        $monthlyRevenue = (float) (clone $monthlyQuery)->sum('total_harga');
        $previousRevenue = (float) (clone $previousMonthQuery)->sum('total_harga');

        $monthlyTransactions = (int) (clone $monthlyQuery)->count();

        $avgOrderValue = $monthlyTransactions > 0
            ? $monthlyRevenue / $monthlyTransactions
            : 0;

        $uniqueCustomers = (int) (clone $monthlyQuery)
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

        $revenueGrowth = $previousRevenue > 0
            ? (($monthlyRevenue - $previousRevenue) / $previousRevenue) * 100
            : ($monthlyRevenue > 0 ? 100 : 0);

        $newCustomers = (int) Pelanggan::query()
            ->whereBetween('tanggal_daftar', [$startOfMonth, $endOfMonth])
            ->count();

        $feedbackCount = (int) Feedback::query()
            ->whereBetween('tanggal_feedback', [$startOfMonth, $endOfMonth])
            ->count();

        $avgRating = (float) Feedback::query()
            ->whereBetween('tanggal_feedback', [$startOfMonth, $endOfMonth])
            ->avg('rating');

        $transaksi = Transaksi::with(['pelanggan', 'operator'])
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10);

        $trendData = Transaksi::query()
            ->select(
                DB::raw('WEEK(tanggal_transaksi, 1) as minggu'),
                DB::raw('COUNT(*) as trx'),
                DB::raw('SUM(total_harga) as revenue')
            )
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->groupBy('minggu')
            ->orderBy('minggu')
            ->get()
            ->map(fn ($row) => [
                'name' => 'W' . $row->minggu,
                'trx' => (int) $row->trx,
                'revenue' => (float) $row->revenue,
            ])
            ->values()
            ->toArray();

        $paymentMethodExpression = "COALESCE(NULLIF(metode_bayar, ''), 'Tidak dicatat')";

        $paymentData = Transaksi::query()
            ->select(
                DB::raw($paymentMethodExpression . ' as name'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw($paymentMethodExpression))
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'count' => (int) $row->count,
                'total' => (float) $row->total,
            ])
            ->values()
            ->toArray();

        $segmentLabels = [
            'baru' => 'Baru',
            'aktif' => 'Aktif',
            'potensial_loyal' => 'Potensial Loyal',
            'loyal' => 'Loyal',
            'tidak_aktif' => 'Tidak Aktif',
        ];

        $segmentColors = [
            'baru' => '#5B8EA8',
            'aktif' => '#3F7D58',
            'potensial_loyal' => '#C7955B',
            'loyal' => '#D4A853',
            'tidak_aktif' => '#8A8077',
        ];

        $segmentData = Pelanggan::query()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(fn ($row) => [
                'key' => $row->status,
                'label' => $segmentLabels[$row->status] ?? ucfirst((string) $row->status),
                'count' => (int) $row->count,
                'color' => $segmentColors[$row->status] ?? '#6F4E37',
            ])
            ->values()
            ->toArray();

        $topProducts = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select(
                'produk.nama_produk',
                'produk.kategori',
                DB::raw('SUM(detail_transaksi.jumlah) as qty'),
                DB::raw('SUM(detail_transaksi.subtotal) as revenue')
            )
            ->whereBetween('transaksi.tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.kategori')
            ->orderByDesc('qty')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'nama_produk' => $row->nama_produk,
                'kategori' => $row->kategori ?: 'Menu',
                'qty' => (int) $row->qty,
                'revenue' => (float) $row->revenue,
            ])
            ->values()
            ->toArray();

        $recentFeedback = Feedback::with(['pelanggan', 'produk'])
            ->whereBetween('tanggal_feedback', [$startOfMonth, $endOfMonth])
            ->orderByDesc('tanggal_feedback')
            ->limit(5)
            ->get();

        $stats = [
            'monthly_revenue' => $monthlyRevenue,
            'previous_revenue' => $previousRevenue,
            'revenue_growth' => $revenueGrowth,
            'monthly_transactions' => $monthlyTransactions,
            'avg_order_value' => $avgOrderValue,
            'unique_customers' => $uniqueCustomers,
            'new_customers' => $newCustomers,
            'feedback_count' => $feedbackCount,
            'avg_rating' => $avgRating,
        ];

        return view('laporan.index', compact(
            'transaksi',
            'trendData',
            'paymentData',
            'segmentData',
            'topProducts',
            'recentFeedback',
            'stats'
        ));
    }
}
