<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function stats(): array
    {
        $customers = Pelanggan::withCount(['transaksi', 'feedback'])->with('feedback')->get();
        $sumLoyalty = 0;

        foreach ($customers as $customer) {
            $totalTrx = $customer->transaksi_count;
            $avgRating = $customer->feedback->count() > 0
                ? $customer->feedback->avg('rating')
                : 0;

            $repeatScore = min($totalTrx * 10, 100);
            $ratingScore = ($avgRating / 5) * 100;
            $feedbackScore = min($customer->feedback->count() * 33, 100);
            $sumLoyalty += (0.4 * $repeatScore) + (0.4 * $ratingScore) + (0.2 * $feedbackScore);
        }

        $feedbacks = Feedback::all();

        return [
            'total_customers' => Pelanggan::count(),
            'total_transactions' => Transaksi::count(),
            'total_feedbacks' => $feedbacks->count(),
            'avg_rating' => $feedbacks->count() > 0
                ? number_format($feedbacks->avg('rating'), 1)
                : '0',
            'loyalty_score' => $customers->count() > 0
                ? number_format($sumLoyalty / $customers->count(), 1)
                : '0',
        ];
    }

    public function monthlySales(): array
    {
        return Transaksi::select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('SUM(total_harga) as total')
        )
            ->whereYear('tanggal_transaksi', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->map(fn ($row) => [
                'name' => date('M', mktime(0, 0, 0, $row->bulan, 1)),
                'sales' => (float) $row->total,
            ])
            ->toArray();
    }

    public function satisfactionDistribution(): array
    {
        $puas = Feedback::where('rating', '>=', 4)->count();
        $netral = Feedback::whereBetween('rating', [3, 3])->count();
        $kecewa = Feedback::where('rating', '<=', 2)->count();

        return [
            ['name' => 'Puas', 'value' => $puas],
            ['name' => 'Netral', 'value' => $netral],
            ['name' => 'Kecewa', 'value' => $kecewa],
        ];
    }
}
