@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-6">
    <header class="mb-8">
        <p class="font-serif italic text-lg text-primary">Selamat datang, {{ session('staff.nama') }}</p>
        <h2 class="text-3xl font-bold">Overview</h2>
    </header>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="card p-5"><p class="text-xs text-gray-400 uppercase">Total Pelanggan</p><p class="text-3xl font-bold">{{ $stats['total_customers'] }}</p></div>
        <div class="card p-5"><p class="text-xs text-gray-400 uppercase">Transaksi</p><p class="text-3xl font-bold">{{ $stats['total_transactions'] }}</p></div>
        <div class="card p-5"><p class="text-xs text-gray-400 uppercase">Rating Rata-rata</p><p class="text-3xl font-bold">{{ $stats['avg_rating'] }}</p></div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 uppercase">Loyalty Score</p>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-bold">{{ $stats['loyalty_score'] }}</span>
                <span class="loyalty-badge">{{ (float)$stats['loyalty_score'] > 70 ? 'Excellent' : ((float)$stats['loyalty_score'] > 40 ? 'Good' : 'Low') }}</span>
            </div>
        </div>
    </div>
    @if(session('staff.type') === 'admin')
    <div class="grid md:grid-cols-2 gap-6">
        <div class="card p-4"><h3 class="font-semibold mb-4 text-sm">Tren Penjualan ({{ date('Y') }})</h3><canvas id="salesChart" height="200"></canvas></div>
        <div class="card p-4"><h3 class="font-semibold mb-4 text-sm">Distribusi Kepuasan</h3><canvas id="satisfactionChart" height="200"></canvas></div>
    </div>
    @endif
</div>
@if(session('staff.type') === 'admin')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('salesChart'), { type: 'bar', data: { labels: @json(array_column($monthlySales, 'name')), datasets: [{ label: 'Penjualan', data: @json(array_column($monthlySales, 'sales')), backgroundColor: '#6F4E37', borderRadius: 4 }] }, options: { plugins: { legend: { display: false } } } });
new Chart(document.getElementById('satisfactionChart'), { type: 'doughnut', data: { labels: @json(array_column($satisfaction, 'name')), datasets: [{ data: @json(array_column($satisfaction, 'value')), backgroundColor: ['#4CAF50','#FF9800','#F44336'] }] } });
</script>
@endpush
@endif
@endsection
