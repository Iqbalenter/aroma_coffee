@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-8">
    <!-- Hero Banner (Memberi kesan Startup/Skripsi Premium) -->
    <div class="relative bg-primary rounded-3xl overflow-hidden shadow-xl">
        <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=1000&auto=format&fit=crop')] bg-cover bg-center mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary to-transparent opacity-90"></div>
        <div class="relative p-8 md:p-12 flex flex-col md:flex-row items-center justify-between z-10">
            <div class="text-white space-y-4 max-w-2xl">
                <p class="font-serif italic text-accent text-xl">Selamat meracik strategi,</p>
                <h2 class="text-4xl md:text-5xl font-bold font-serif leading-tight">Halo, {{ session('staff.nama') }} 👋</h2>
                <p class="text-white/80 text-sm md:text-base leading-relaxed">Ini adalah <strong>CJM Data Platform</strong> eksperimental untuk Aroma Coffee Bland. Di sini kita memantau retensi dan menganalisis sentimen suara pelanggan secara kualitatif untuk strategi loyalitas.</p>
            </div>
            <div class="hidden md:block bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center">
                <p class="text-[10px] uppercase tracking-widest text-accent mb-1 font-semibold">Tahun Analisis</p>
                <p class="text-3xl font-bold text-white font-serif">{{ date('Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform -z-10"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Total Pelanggan</p>
                    <p class="text-4xl font-bold text-dark mt-1">{{ $stats['total_customers'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 text-blue-600 rounded-xl"><i data-lucide="users" class="w-6 h-6"></i></div>
            </div>
            <p class="text-xs text-gray-400">Total data pelanggan di sistem</p>
        </div>

        <div class="card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-110 transition-transform -z-10"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Transaksi (Penjualan)</p>
                    <p class="text-4xl font-bold text-dark mt-1">{{ $stats['total_transactions'] }}</p>
                </div>
                <div class="p-3 bg-green-100 text-green-600 rounded-xl"><i data-lucide="receipt" class="w-6 h-6"></i></div>
            </div>
            <p class="text-xs text-gray-400">Terdata sepanjang waktu</p>
        </div>

        <div class="card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 rounded-full group-hover:scale-110 transition-transform -z-10"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Rating Keseluruhan</p>
                    <p class="text-4xl font-bold text-dark mt-1">{{ $stats['avg_rating'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 text-yellow-600 rounded-xl"><i data-lucide="star" class="w-6 h-6"></i></div>
            </div>
            <p class="text-xs text-gray-400">Rata-rata dari skor 1.0 - 5.0</p>
        </div>

        <div class="card p-6 relative overflow-hidden group border-accent/30 bg-gradient-to-br from-white to-amber-50">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-100/50 rounded-full group-hover:scale-110 transition-transform -z-10"></div>
            <div class="flex justify-between items-start mb-2">
                <p class="text-[10px] text-accent uppercase font-bold tracking-wider">Indeks Loyalitas</p>
                <div class="p-2 bg-gradient-to-r from-accent to-yellow-500 text-white rounded-lg shadow-sm"><i data-lucide="award" class="w-5 h-5"></i></div>
            </div>
            <div class="flex items-end gap-3 mt-1">
                <span class="text-4xl font-bold text-primary">{{ $stats['loyalty_score'] }}</span>
                <span class="loyalty-badge mb-2">{{ (float)$stats['loyalty_score'] > 70 ? 'Excellent' : ((float)$stats['loyalty_score'] > 40 ? 'Good' : 'Low') }}</span>
            </div>
            <p class="text-xs text-primary/60 mt-3 font-medium">Berdasarkan RFM Analysis</p>
        </div>
    </div>

    @if(session('staff.type') === 'admin')
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="card p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-lg text-dark">Tren Transaksi Bulanan</h3>
                    <p class="text-xs text-gray-500">Volume transaksi dalam tahun berjalan</p>
                </div>
                <button class="bg-gray-100 p-2 rounded-lg text-gray-500 hover:text-dark"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Satisfaction Chart -->
        <div class="card p-6">
            <h3 class="font-bold text-lg text-dark mb-1">Peta Sentimen</h3>
            <p class="text-xs text-gray-500 mb-6">Distribusi emosi/kepuasan pelanggan</p>
            <div class="relative h-48 w-full flex justify-center items-center">
                <canvas id="satisfactionChart"></canvas>
            </div>
            <!-- Custom Legend -->
            <div class="mt-6 flex flex-col gap-3">
                @foreach($satisfaction as $item)
                @php
                    $colors = ['Positif' => 'bg-green-500', 'Netral' => 'bg-yellow-500', 'Negatif' => 'bg-red-500'];
                    $color = $colors[$item['name']] ?? 'bg-primary';
                @endphp
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $color }}"></span>
                        <span class="text-gray-600">{{ $item['name'] }}</span>
                    </div>
                    <span class="font-bold text-dark">{{ $item['value'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@if(session('staff.type') === 'admin')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#9CA3AF';

    // Gradient Setup untuk sales chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(212, 168, 83, 0.8)'); // Accent color
    gradient.addColorStop(1, 'rgba(212, 168, 83, 0.1)');

    new Chart(ctx, { 
        type: 'bar', 
        data: { 
            labels: @json(array_column($monthlySales, 'name')), 
            datasets: [{ 
                label: 'Penjualan', 
                data: @json(array_column($monthlySales, 'sales')), 
                backgroundColor: gradient, 
                hoverBackgroundColor: '#5A3D2B',
                borderRadius: 6,
                borderSkipped: false
            }] 
        }, 
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#2C1E16',
                    padding: 12,
                    titleFont: { size: 13, family: 'Inter' },
                    bodyFont: { size: 14, weight: 'bold' },
                    displayColors: false,
                    cornerRadius: 8
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, border: { display: false } },
                x: { grid: { display: false, drawBorder: false }, border: { display: false } }
            }
        } 
    });

    const ctxSat = document.getElementById('satisfactionChart').getContext('2d');
    new Chart(ctxSat, { 
        type: 'doughnut', 
        data: { 
            labels: @json(array_column($satisfaction, 'name')), 
            datasets: [{ 
                data: @json(array_column($satisfaction, 'value')), 
                backgroundColor: ['#22c55e', '#eab308', '#ef4444'], // update to tailwind exact colors (green, yellow, red)
                borderWidth: 0,
                hoverOffset: 4
            }] 
        },
        options: {
            cutout: '75%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush
@endif
@endsection
