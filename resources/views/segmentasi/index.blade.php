@extends('layouts.app')
@section('title', 'Segmentasi')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold">Segmentasi Pelanggan</h1><p class="text-gray-500">Berdasarkan frekuensi transaksi dan loyalitas</p></div>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="card p-6"><h3 class="font-bold mb-4">Distribusi Segmen</h3><canvas id="segChart" height="220"></canvas></div>
        <div class="space-y-4">
            @foreach($segments as $key => $seg)
            <div class="card p-4 flex justify-between border-l-4" style="border-left-color:{{ $seg['color'] }}">
                <div><h4 class="font-bold text-sm">{{ $seg['label'] }}</h4><p class="text-[10px] text-gray-500">{{ $seg['desc'] }}</p></div>
                <span class="text-xl font-bold">{{ $seg['count'] }} <span class="text-xs font-normal text-gray-500">orang</span></span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('segChart'), { type: 'doughnut', data: {
    labels: @json($chartData->pluck('name')),
    datasets: [{ data: @json($chartData->pluck('value')), backgroundColor: @json($chartData->pluck('color')) }]
}});
</script>
@endpush
@endsection
