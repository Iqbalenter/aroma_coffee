@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold">Laporan Loyalitas & Transaksi</h1><p class="text-gray-500">Analisis tren bulan ini</p></div>
    <div class="card p-6"><h3 class="font-bold mb-4">Tren Transaksi (Bulan Ini)</h3><canvas id="trendChart" height="200"></canvas></div>
    <div class="card overflow-x-auto">
        <div class="p-4 border-b font-bold">Raw Data</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr>
                <th class="px-6 py-3 text-left">ID</th><th class="px-6 py-3 text-left">Tanggal</th><th class="px-6 py-3 text-left">Total</th>
            </tr></thead>
            <tbody>
            @forelse($transaksi as $t)
            <tr class="border-b"><td class="px-6 py-4">#{{ $t->id_transaksi }}</td>
                <td class="px-6 py-4">{{ $t->tanggal_transaksi->format('d M Y H:i') }}</td>
                <td class="px-6 py-4 font-bold text-primary">Rp {{ number_format($t->total_harga,0,',','.') }}</td></tr>
            @empty
            <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $transaksi->links() }}</div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>new Chart(document.getElementById('trendChart'),{type:'line',data:{labels:@json(array_column($trendData,'name')),datasets:[{label:'Transaksi',data:@json(array_column($trendData,'trx')),borderColor:'#D4A853',tension:.3}]}});</script>
@endpush
@endsection
