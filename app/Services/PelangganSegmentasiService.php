<?php

namespace App\Services;

use App\Models\Pelanggan;

class PelangganSegmentasiService
{
    public function updateStatus(Pelanggan $pelanggan): void
    {
        $totalTrx = $pelanggan->transaksi()->count();
        $feedbacks = $pelanggan->feedback;
        $avgRating = $feedbacks->count() > 0 ? $feedbacks->avg('rating') : 0;

        $status = 'baru';

        if ($totalTrx === 0) {
            $status = 'baru';
        } elseif ($totalTrx >= 5 && $avgRating >= 4) {
            $status = 'loyal';
        } elseif ($totalTrx >= 3 && $avgRating >= 3.5) {
            $status = 'potensial_loyal';
        } elseif ($totalTrx >= 1) {
            $status = 'aktif';
        }

        $lastTrx = $pelanggan->transaksi()->latest('tanggal_transaksi')->first();
        if ($lastTrx && $lastTrx->tanggal_transaksi->lt(now()->subMonths(3)) && $totalTrx > 0) {
            $status = 'tidak_aktif';
        }

        $pelanggan->update(['status' => $status]);
    }

    public function updateAll(): void
    {
        Pelanggan::with(['transaksi', 'feedback'])->chunk(50, function ($pelangganList) {
            foreach ($pelangganList as $pelanggan) {
                $this->updateStatus($pelanggan);
            }
        });
    }
}
