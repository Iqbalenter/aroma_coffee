@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Riwayat Transaksi</h1>
        @if(session('staff.type') === 'operator')
        <a href="{{ route('transaksi.create') }}" class="btn">+ Input Transaksi</a>
        @endif
    </div>
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr>
                <th class="px-6 py-3 text-left">ID</th><th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Pelanggan</th><th class="px-6 py-3 text-left">Metode</th>
                <th class="px-6 py-3 text-left">Total</th>
                @if(session('staff.type') === 'admin')<th class="px-6 py-3">Aksi</th>@endif
            </tr></thead>
            <tbody>
            @forelse($transaksi as $t)
            <tr class="border-b">
                <td class="px-6 py-4">#{{ $t->id_transaksi }}</td>
                <td class="px-6 py-4">{{ $t->tanggal_transaksi->format('d M Y H:i') }}</td>
                <td class="px-6 py-4">{{ $t->pelanggan->nama ?? '-' }}</td>
                <td class="px-6 py-4">{{ $t->metode_bayar }}</td>
                <td class="px-6 py-4 font-bold text-primary">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                @if(session('staff.type') === 'admin')
                <td class="px-6 py-4">
                    <form action="{{ route('transaksi.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-danger">Hapus</button></form>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $transaksi->links() }}</div>
    </div>
</div>
@endsection
