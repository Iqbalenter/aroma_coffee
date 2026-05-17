@extends('layouts.app')
@section('title', 'Produk')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manajemen Produk</h1>
        <button onclick="openModal()" class="btn">+ Tambah Produk</button>
    </div>
    <form method="GET" class="card p-4"><input name="search" value="{{ $search }}" placeholder="Cari produk..." class="input max-w-sm"></form>
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr>
                <th class="px-6 py-3 text-left">Nama</th><th class="px-6 py-3 text-left">Kategori</th>
                <th class="px-6 py-3 text-left">Harga</th><th class="px-6 py-3 text-left">Status</th><th class="px-6 py-3">Aksi</th>
            </tr></thead>
            <tbody>
            @forelse($produk as $p)
            <tr class="border-b">
                <td class="px-6 py-4 font-medium">{{ $p->nama_produk }}</td>
                <td class="px-6 py-4">{{ $p->kategori }}</td>
                <td class="px-6 py-4">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $p->status === 'tersedia' ? 'bg-success/10 text-success' : 'bg-gray-100' }}">{{ ucfirst($p->status) }}</span></td>
                <td class="px-6 py-4">
                    <button type="button" class="text-accent mr-2" onclick='editProduk(@json($p))'>Edit</button>
                    <form action="{{ route('produk.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-danger">Hapus</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Produk tidak ditemukan</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $produk->withQueryString()->links() }}</div>
    </div>
</div>
<div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="card w-full max-w-md p-6">
        <h2 id="modalTitle" class="text-lg font-bold mb-4">Tambah Produk</h2>
        <form id="produkForm" method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data" class="space-y-4">@csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div><label class="text-sm block mb-1">Nama Produk</label><input name="nama_produk" id="f_nama" class="input" required></div>
            <div><label class="text-sm block mb-1">Kategori</label>
                <select name="kategori" id="f_kategori" class="input">
                    <option>Coffee</option><option>Non-Coffee</option><option>Snack</option><option>Pastry</option>
                </select>
            </div>
            <div><label class="text-sm block mb-1">Harga (Rp)</label><input name="harga" id="f_harga" type="number" class="input" required></div>
            <div><label class="text-sm block mb-1">Status</label>
                <select name="status" id="f_status" class="input"><option value="tersedia">Tersedia</option><option value="habis">Habis</option></select>
            </div>
            <div><label class="text-sm block mb-1">Gambar</label><input name="gambar" type="file" accept="image/*" class="input"></div>
            <div class="flex gap-2 justify-end"><button type="button" class="btn-outline" onclick="closeModal()">Batal</button><button class="btn">Simpan</button></div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function openModal(){ resetForm('{{ route('produk.store') }}','POST'); document.getElementById('modal').classList.remove('hidden'); }
function closeModal(){ document.getElementById('modal').classList.add('hidden'); }
function resetForm(action,method){ document.getElementById('modalTitle').textContent='Tambah Produk'; document.getElementById('produkForm').action=action; document.getElementById('formMethod').value=method; }
function editProduk(p){ document.getElementById('modalTitle').textContent='Edit Produk'; document.getElementById('produkForm').action='/produk/'+p.id_produk; document.getElementById('formMethod').value='PUT'; document.getElementById('f_nama').value=p.nama_produk; document.getElementById('f_kategori').value=p.kategori; document.getElementById('f_harga').value=p.harga; document.getElementById('f_status').value=p.status; document.getElementById('modal').classList.remove('hidden'); }
</script>
@endpush
@endsection
