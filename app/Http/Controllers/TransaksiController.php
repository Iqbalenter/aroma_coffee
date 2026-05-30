<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Services\PelangganSegmentasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    protected PelangganSegmentasiService $segmentasi;

    public function __construct(PelangganSegmentasiService $segmentasi)
    {
        $this->segmentasi = $segmentasi;
    }

    public function index(Request $request)
    {
        if (session('staff.type') !== 'admin') {
            return redirect()
                ->route('transaksi.create')
                ->with('error', 'Operator hanya dapat mencatat transaksi baru, tidak dapat melihat data transaksi.');
        }

        $search = trim((string) $request->get('search', ''));
        $metode = $request->get('metode_bayar');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Transaksi::query()
            ->with(['pelanggan', 'operator', 'detail.produk']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id_transaksi', 'like', "%{$search}%")
                    ->orWhere('metode_bayar', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($pelanggan) use ($search) {
                        $pelanggan->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('nomor_hp', 'like', "%{$search}%");
                    })
                    ->orWhereHas('operator', function ($operator) use ($search) {
                        $operator->where('nama', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        if ($metode) {
            $query->where('metode_bayar', $metode);
        }

        if ($dateFrom) {
            $query->whereDate('tanggal_transaksi', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('tanggal_transaksi', '<=', $dateTo);
        }

        $transaksi = $query
            ->orderByDesc('tanggal_transaksi')
            ->paginate(12)
            ->withQueryString();

        $startOfMonth = now()->copy()->startOfMonth();
        $endOfMonth = now()->copy()->endOfMonth();

        $startOfToday = now()->copy()->startOfDay();
        $endOfToday = now()->copy()->endOfDay();

        $monthlyQuery = Transaksi::query()
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth]);

        $todayQuery = Transaksi::query()
            ->whereBetween('tanggal_transaksi', [$startOfToday, $endOfToday]);

        $monthlyRevenue = (float) (clone $monthlyQuery)->sum('total_harga');
        $monthlyTransactions = (int) (clone $monthlyQuery)->count();

        $todayRevenue = (float) (clone $todayQuery)->sum('total_harga');
        $todayTransactions = (int) (clone $todayQuery)->count();

        $avgOrderValue = $monthlyTransactions > 0
            ? $monthlyRevenue / $monthlyTransactions
            : 0;

        $uniqueCustomers = (int) (clone $monthlyQuery)
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

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
            ->values();

        $trendData = Transaksi::query()
            ->select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw('DATE(tanggal_transaksi)'))
            ->orderBy('tanggal')
            ->get()
            ->map(fn ($row) => [
                'label' => Carbon::parse($row->tanggal)->format('d M'),
                'count' => (int) $row->count,
                'total' => (float) $row->total,
            ])
            ->values();

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
            ->values();

        $paymentOptions = Transaksi::query()
            ->whereNotNull('metode_bayar')
            ->where('metode_bayar', '!=', '')
            ->distinct()
            ->orderBy('metode_bayar')
            ->pluck('metode_bayar')
            ->filter()
            ->values();

        if ($paymentOptions->isEmpty()) {
            $paymentOptions = collect(['Cash', 'QRIS', 'Transfer']);
        }

        $stats = [
            'monthly_revenue' => $monthlyRevenue,
            'monthly_transactions' => $monthlyTransactions,
            'today_revenue' => $todayRevenue,
            'today_transactions' => $todayTransactions,
            'avg_order_value' => $avgOrderValue,
            'unique_customers' => $uniqueCustomers,
        ];

        return view('transaksi.index', compact(
            'transaksi',
            'search',
            'metode',
            'dateFrom',
            'dateTo',
            'paymentOptions',
            'paymentData',
            'trendData',
            'topProducts',
            'stats'
        ));
    }

    public function create()
    {
        if (session('staff.type') !== 'operator') {
            return redirect()
                ->route('transaksi.index')
                ->with('error', 'Hanya operator yang dapat mencatat transaksi baru.');
        }

        $pelanggan = Pelanggan::orderBy('nama')->get();

        $produk = Produk::tersedia()
            ->orderBy('kategori')
            ->orderBy('nama_produk')
            ->get();

        return view('transaksi.create', compact('pelanggan', 'produk'));
    }

    public function store(Request $request)
    {
        if (session('staff.type') !== 'operator') {
            abort(403, 'Hanya operator yang dapat mencatat transaksi.');
        }

        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'metode_bayar' => 'required|string|max:50',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            $details = [];

            foreach ($request->items as $item) {
                $produk = Produk::where('id_produk', $item['id_produk'])
                    ->where('status', 'tersedia')
                    ->firstOrFail();

                $jumlah = (int) $item['jumlah'];
                $subtotal = (float) $produk->harga * $jumlah;
                $total += $subtotal;

                $details[] = [
                    'id_produk' => $produk->id_produk,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $subtotal,
                ];
            }

            $transaksi = Transaksi::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_operator' => session('staff.id'),
                'metode_bayar' => $request->metode_bayar,
                'catatan' => $request->catatan,
                'total_harga' => $total,
                'tanggal_transaksi' => now(),
            ]);

            foreach ($details as $detail) {
                $transaksi->detail()->create($detail);
            }

            $pelanggan = Pelanggan::find($request->id_pelanggan);

            if ($pelanggan) {
                $this->segmentasi->updateStatus($pelanggan);
            }
        });

        return redirect()
            ->route('transaksi.create')
            ->with('success', 'Transaksi berhasil disimpan. Operator dapat mencatat transaksi berikutnya.');
    }

    public function destroy(Transaksi $transaksi)
    {
        if (session('staff.type') !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus transaksi.');
        }

        $pelanggan = $transaksi->pelanggan;

        $transaksi->delete();

        if ($pelanggan) {
            $this->segmentasi->updateStatus($pelanggan);
        }

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
