<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $produk = Produk::when($search, fn ($q) => $q->where('nama_produk', 'like', "%{$search}%"))
            ->orderBy('nama_produk')
            ->paginate(15);

        return view('produk.index', compact('produk', 'search'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduk($request);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }
        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $this->validateProduk($request, $produk);
        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }
        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->detailTransaksi()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena digunakan dalam transaksi.');
        }
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validateProduk(Request $request, ?Produk $produk = null): array
    {
        return $request->validate([
            'nama_produk' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
            'gambar' => 'nullable|image|max:2048',
        ]);
    }
}
