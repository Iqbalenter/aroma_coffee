<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;

class SegmentasiController extends Controller
{
    public function index()
    {
        $segments = [
            'baru' => ['label' => 'Baru', 'color' => '#2196F3', 'desc' => 'Pelanggan baru transaksi pertama'],
            'aktif' => ['label' => 'Aktif', 'color' => '#4CAF50', 'desc' => 'Sering bertransaksi tapi belum terikat'],
            'potensial_loyal' => ['label' => 'Potensial Loyal', 'color' => '#FF9800', 'desc' => 'Sering transaksi dan rating mulai tinggi'],
            'loyal' => ['label' => 'Loyal', 'color' => '#D4A853', 'desc' => 'Pelanggan setia dengan repeat purchase & rating tinggi'],
            'tidak_aktif' => ['label' => 'Tidak Aktif', 'color' => '#9E9E9E', 'desc' => 'Lama tidak bertransaksi'],
        ];

        $counts = Pelanggan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        foreach ($segments as $key => &$seg) {
            $seg['count'] = $counts[$key] ?? 0;
        }

        $chartData = collect($segments)
            ->map(fn ($s, $name) => ['name' => $s['label'], 'value' => $s['count'], 'color' => $s['color']])
            ->filter(fn ($d) => $d['value'] > 0)
            ->values();

        return view('segmentasi.index', compact('segments', 'chartData'));
    }
}
