@extends('layouts.app')
@section('title', 'Pelanggan')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Pelanggan</h1>
        <button onclick="openModal()" class="btn">+ Pelanggan Baru</button>
    </div>
    <form method="GET" class="card p-4"><input type="search" name="search" value="{{ $search }}" placeholder="Cari pelanggan..." class="input max-w-sm"></form>
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr>
                <th class="px-6 py-3 text-left">Nama</th><th class="px-6 py-3 text-left">Kontak</th>
                <th class="px-6 py-3 text-left">Tgl Daftar</th><th class="px-6 py-3 text-left">Status</th><th class="px-6 py-3">Aksi</th>
            </tr></thead>
            <tbody>
            @forelse($pelanggan as $p)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 font-medium">{{ $p->nama }}</td>
                <td class="px-6 py-4">{{ $p->nomor_hp }}<br><span class="text-xs text-gray-400">{{ $p->email }}</span></td>
                <td class="px-6 py-4">{{ $p->tanggal_daftar?->format('d M Y') }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs bg-primary/10 text-primary">{{ $p->status_label }}</span></td>
                <td class="px-6 py-4">
                    <button type="button" class="text-accent mr-2" onclick='editPelanggan(@json($p))'>Edit</button>
                    <form action="{{ route('pelanggan.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-danger">Hapus</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $pelanggan->withQueryString()->links() }}</div>
    </div>
</div>
<div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="card w-full max-w-md p-6">
        <h2 id="modalTitle" class="text-lg font-bold mb-4">Tambah Pelanggan</h2>
        <form id="pelangganForm" method="POST" action="{{ route('pelanggan.store') }}" class="space-y-4">@csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div><label class="text-sm block mb-1">Nama</label><input name="nama" id="f_nama" class="input" required></div>
            <div><label class="text-sm block mb-1">No HP</label><input name="nomor_hp" id="f_hp" class="input"></div>
            <div><label class="text-sm block mb-1">Email</label><input name="email" id="f_email" type="email" class="input"></div>
            <div><label class="text-sm block mb-1">Alamat</label><input name="alamat" id="f_alamat" class="input"></div>
            <div class="flex gap-2 justify-end"><button type="button" class="btn-outline" onclick="closeModal()">Batal</button><button class="btn">Simpan</button></div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function openModal(){ document.getElementById('modalTitle').textContent='Tambah Pelanggan'; document.getElementById('pelangganForm').action='{{ route('pelanggan.store') }}'; document.getElementById('formMethod').value='POST'; ['f_nama','f_hp','f_email','f_alamat'].forEach(id=>document.getElementById(id).value=''); document.getElementById('modal').classList.remove('hidden'); }
function closeModal(){ document.getElementById('modal').classList.add('hidden'); }
function editPelanggan(p){ document.getElementById('modalTitle').textContent='Edit Pelanggan'; document.getElementById('pelangganForm').action='/pelanggan/'+p.id_pelanggan; document.getElementById('formMethod').value='PUT'; document.getElementById('f_nama').value=p.nama; document.getElementById('f_hp').value=p.nomor_hp||''; document.getElementById('f_email').value=p.email||''; document.getElementById('f_alamat').value=p.alamat||''; document.getElementById('modal').classList.remove('hidden'); }
</script>
@endpush
@endsection
