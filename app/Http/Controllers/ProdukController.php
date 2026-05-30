<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $kategori = $request->get('kategori');
        $status = $request->get('status');

        $defaultKategori = collect([
            'Coffee',
            'Non-Coffee',
            'Snack',
            'Pastry',
        ]);

        $kategoriOptions = Produk::query()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values();

        if ($kategoriOptions->isEmpty()) {
            $kategoriOptions = $defaultKategori;
        }

        $query = Produk::query()
            ->withCount(['detailTransaksi', 'feedback'])
            ->withSum('detailTransaksi as total_terjual', 'jumlah')
            ->withSum('detailTransaksi as total_pendapatan', 'subtotal')
            ->withAvg('feedback as rata_rating', 'rating');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if (in_array($status, ['tersedia', 'habis'], true)) {
            $query->where('status', $status);
        }

        $produk = $query
            ->orderBy('kategori')
            ->orderBy('nama_produk')
            ->paginate(12)
            ->withQueryString();

        $totalProduk = Produk::count();
        $produkTersedia = Produk::where('status', 'tersedia')->count();
        $produkHabis = Produk::where('status', 'habis')->count();
        $avgHarga = (float) Produk::avg('harga');

        $statusRaw = Produk::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusData = collect([
            'tersedia' => [
                'label' => 'Tersedia',
                'count' => (int) $statusRaw->get('tersedia', 0),
                'color' => '#3F7D58',
            ],
            'habis' => [
                'label' => 'Habis',
                'count' => (int) $statusRaw->get('habis', 0),
                'color' => '#B4533C',
            ],
        ])->values();

        $categoryExpression = "COALESCE(NULLIF(kategori, ''), 'Tanpa Kategori')";

        $categoryData = Produk::query()
            ->select(
                DB::raw($categoryExpression . ' as label'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw($categoryExpression))
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->label,
                'count' => (int) $row->count,
            ])
            ->values();

        $bestSellers = Produk::query()
            ->withSum('detailTransaksi as total_terjual', 'jumlah')
            ->withSum('detailTransaksi as total_pendapatan', 'subtotal')
            ->withAvg('feedback as rata_rating', 'rating')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $stats = [
            'total_produk' => $totalProduk,
            'produk_tersedia' => $produkTersedia,
            'produk_habis' => $produkHabis,
            'avg_harga' => $avgHarga,
        ];

        return view('produk.index', compact(
            'produk',
            'search',
            'kategori',
            'status',
            'kategoriOptions',
            'statusData',
            'categoryData',
            'bestSellers',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduk($request);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
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

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->detailTransaksi()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        }

        if ($produk->feedback()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah memiliki feedback pelanggan.');
        }

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function validateProduk(Request $request, ?Produk $produk = null): array
    {
        return $request->validate([
            'nama_produk' => ['required', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'harga' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:tersedia,habis'],
            'gambar' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
