<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10);

        $trendData = Transaksi::select(
            DB::raw('WEEK(tanggal_transaksi, 1) as minggu'),
            DB::raw('COUNT(*) as trx')
        )
            ->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->groupBy('minggu')
            ->orderBy('minggu')
            ->get()
            ->map(fn ($r) => ['name' => 'W'.$r->minggu, 'trx' => $r->trx])
            ->toArray();

        return view('laporan.index', compact('transaksi', 'trendData'));
    }
}
