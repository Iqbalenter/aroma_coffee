<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Services\PelangganSegmentasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function __construct(private PelangganSegmentasiService $segmentasi) {}

    public function index()
    {
        $transaksi = Transaksi::with(['pelanggan', 'operator'])
            ->orderByDesc('tanggal_transaksi')
            ->paginate(15);

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $produk = Produk::tersedia()->orderBy('nama_produk')->get();

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
                $produk = Produk::findOrFail($item['id_produk']);
                $subtotal = $produk->harga * $item['jumlah'];
                $total += $subtotal;
                $details[] = [
                    'id_produk' => $produk->id_produk,
                    'jumlah' => $item['jumlah'],
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
            $this->segmentasi->updateStatus($pelanggan);
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $pelanggan = $transaksi->pelanggan;
        $transaksi->delete();
        $this->segmentasi->updateStatus($pelanggan);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
