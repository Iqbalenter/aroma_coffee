@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $rawLoyalty = data_get($stats, 'loyalty_score', 0);
    $loyaltyScore = is_numeric($rawLoyalty)
        ? (float) $rawLoyalty
        : (float) preg_replace('/[^0-9.]/', '', (string) $rawLoyalty);

    $loyaltyWidth = min(100, max(0, $loyaltyScore));

    $loyaltyStatus = $loyaltyScore >= 70
        ? 'Excellent'
        : ($loyaltyScore >= 40 ? 'Good' : 'Needs Attention');

    $metrics = [
        [
            'label' => 'Total Pelanggan',
            'value' => number_format((float) data_get($stats, 'total_customers', 0), 0, ',', '.'),
            'desc' => 'Profil pelanggan yang sudah tercatat',
            'icon' => 'users',
        ],
        [
            'label' => 'Total Transaksi',
            'value' => number_format((float) data_get($stats, 'total_transactions', 0), 0, ',', '.'),
            'desc' => 'Aktivitas penjualan sepanjang waktu',
            'icon' => 'receipt',
        ],
        [
            'label' => 'Rating Rata-rata',
            'value' => number_format((float) data_get($stats, 'avg_rating', 0), 1, ',', '.'),
            'desc' => 'Skor pengalaman dari feedback pelanggan',
            'icon' => 'star',
        ],
        [
            'label' => 'Indeks Loyalitas',
            'value' => rtrim(rtrim(number_format($loyaltyScore, 1, ',', '.'), '0'), ',') . '%',
            'desc' => 'Estimasi loyalitas berbasis transaksi dan rating',
            'icon' => 'heart-handshake',
        ],
    ];

    $journeyStages = [
        ['Awareness', 'Pelanggan mengenal Aroma Coffee'],
        ['Consideration', 'Pelanggan membandingkan pilihan'],
        ['Purchase', 'Pelanggan melakukan pembelian'],
        ['Experience', 'Pelanggan merasakan produk dan layanan'],
        ['Retention', 'Pelanggan berpotensi kembali'],
        ['Loyalty', 'Pelanggan menjadi pendukung brand'],
    ];

    $quickActions = session('staff.type') === 'admin'
        ? [
            ['Lihat Journey Map', route('cjm.index'), 'map'],
            ['Analisis Segmentasi', route('segmentasi.index'), 'pie-chart'],
            ['Buka Laporan', route('laporan.index'), 'file-text'],
        ]
        : [
            ['Input Transaksi', route('transaksi.create'), 'calculator'],
            ['Tambah Feedback', route('feedback.create'), 'mic'],
            ['Data Pelanggan', route('pelanggan.index'), 'users'],
        ];
@endphp

<div class="space-y-8">
    <section class="relative overflow-hidden rounded-[2rem] bg-coffee-espresso shadow-soft">
        <div class="absolute inset-0 coffee-grain-bg opacity-45"></div>
        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-coffee-caramel/30 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-coffee-latte/10 blur-3xl"></div>

        <div class="relative grid gap-8 p-6 md:grid-cols-[1.5fr_.8fr] md:p-10">
            <div class="max-w-3xl">
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[.18em] text-coffee-cream">
                    <i data-lucide="coffee" class="h-4 w-4 text-coffee-caramel"></i>
                    Customer Journey Intelligence
                </div>

                <p class="font-serif text-2xl italic text-coffee-caramel">
                    Selamat meracik strategi,
                </p>

                <h1 class="mt-2 font-serif text-4xl font-bold leading-tight text-white md:text-6xl">
                    Halo, {{ session('staff.nama') }}
                </h1>

                <p class="mt-5 max-w-2xl text-sm leading-7 text-white md:text-base">
                    Dashboard ini dirancang sebagai ruang analisis pelanggan Aroma Coffee Bland:
                    membaca transaksi, feedback, sentimen, dan potensi loyalitas dalam satu tampilan
                    yang lebih hangat, modern, dan sesuai karakter coffee shop.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    @foreach($quickActions as [$label, $url, $icon])
                        <a href="{{ $url }}"
                           class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/15">
                            <i data-lucide="{{ $icon }}" class="h-4 w-4 text-coffee-caramel"></i>
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[1.7rem] border border-white/10 bg-white/10 p-5 text-white backdrop-blur-xl">
                <p class="text-[11px] font-bold uppercase tracking-[.20em] text-coffee-caramel">
                    Tahun Analisis
                </p>

                <div class="mt-2 flex items-end justify-between gap-4">
                    <p class="font-serif text-6xl font-bold">{{ date('Y') }}</p>
                    <div class="rounded-2xl bg-white/10 p-3">
                        <i data-lucide="calendar" class="h-7 w-7 text-coffee-cream"></i>
                    </div>
                </div>

                <div class="mt-7">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-semibold text-white/70">Loyalty Health</span>
                        <span class="font-bold text-coffee-cream">{{ $loyaltyStatus }}</span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-gradient-to-r from-coffee-caramel to-coffee-latte"
                             style="width: {{ $loyaltyWidth }}%"></div>
                    </div>

                    <p class="mt-3 text-xs leading-6 text-white/58">
                        Nilai ini membantu membaca apakah pelanggan cenderung baru, aktif,
                        potensial loyal, atau perlu pendekatan ulang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($metrics as $metric)
            <div class="card group relative overflow-hidden p-6">
                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-coffee-caramel/10 transition group-hover:scale-110"></div>

                <div class="relative flex items-start justify-between gap-5">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">
                            {{ $metric['label'] }}
                        </p>

                        <p class="mt-3 text-4xl font-extrabold tracking-tight text-coffee-espresso">
                            {{ $metric['value'] }}
                        </p>
                    </div>

                    <div class="flex h-13 w-13 items-center justify-center rounded-2xl bg-coffee-cream text-coffee-mocha">
                        <i data-lucide="{{ $metric['icon'] }}" class="h-6 w-6"></i>
                    </div>
                </div>

                <p class="relative mt-5 text-sm leading-6 text-coffee-espresso/55">
                    {{ $metric['desc'] }}
                </p>
            </div>
        @endforeach
    </section>

    <section class="card p-6 md:p-7">
        <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-coffee-bronze">
                    Customer Journey Mapping
                </p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-coffee-espresso">
                    Alur Pengalaman Pelanggan Aroma Coffee
                </h2>
            </div>

            <span class="badge w-fit">
                <i data-lucide="sparkles" class="h-4 w-4"></i>
                Coffee-themed CJM
            </span>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            @foreach($journeyStages as $index => [$stage, $description])
                <div class="rounded-3xl border border-coffee-mocha/10 bg-white/55 p-4">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-2xl bg-coffee-espresso text-sm font-extrabold text-white">
                        {{ $index + 1 }}
                    </div>

                    <h3 class="font-bold text-coffee-espresso">{{ $stage }}</h3>
                    <p class="mt-2 text-xs leading-6 text-coffee-espresso/55">
                        {{ $description }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    @if(session('staff.type') === 'admin')
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.45fr_.9fr]">
            <div class="card p-6 md:p-7">
                <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-coffee-bronze">
                            Tren Transaksi
                        </p>
                        <h2 class="mt-2 font-serif text-3xl font-bold text-coffee-espresso">
                            Pergerakan Penjualan Bulanan
                        </h2>
                    </div>

                    <div class="rounded-2xl bg-coffee-cream px-4 py-3 text-sm font-bold text-coffee-mocha">
                        Tahun {{ date('Y') }}
                    </div>
                </div>

                <div class="h-80">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="card p-6 md:p-7">
                <div class="mb-6">
                    <p class="text-[11px] font-extrabold uppercase tracking-[.18em] text-coffee-bronze">
                        Peta Sentimen
                    </p>
                    <h2 class="mt-2 font-serif text-3xl font-bold text-coffee-espresso">
                        Distribusi Kepuasan
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-coffee-espresso/55">
                        Gambaran persepsi pelanggan dari feedback yang masuk.
                    </p>
                </div>

                <div class="mx-auto h-64 max-w-xs">
                    <canvas id="satisfactionChart"></canvas>
                </div>

                <div class="mt-6 space-y-3">
                    @foreach(($satisfaction ?? []) as $item)
                        @php
                            $name = data_get($item, 'name', '-');
                            $value = (float) data_get($item, 'value', 0);
                            $width = min(100, max(0, $value));

                            $color = match ($name) {
                                'Positif' => '#6F4E37',
                                'Netral' => '#C7955B',
                                'Negatif' => '#A15C38',
                                default => '#A7784D',
                            };
                        @endphp

                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2 font-bold text-coffee-espresso">
                                    <span class="h-3 w-3 rounded-full" style="background: {{ $color }}"></span>
                                    {{ $name }}
                                </div>
                                <span class="font-extrabold text-coffee-mocha">{{ $value }}%</span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-coffee-cream">
                                <div class="h-full rounded-full"
                                     style="width: {{ $width }}%; background: {{ $color }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section class="grid gap-6 md:grid-cols-3">
            <a href="{{ route('transaksi.create') }}" class="card p-6 transition hover:-translate-y-1 hover:shadow-soft">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-coffee-espresso text-white">
                    <i data-lucide="calculator" class="h-6 w-6"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-coffee-espresso">Input Transaksi</h3>
                <p class="mt-2 text-sm leading-6 text-coffee-espresso/55">
                    Catat pembelian pelanggan dan detail produk yang terjual.
                </p>
            </a>

            <a href="{{ route('feedback.create') }}" class="card p-6 transition hover:-translate-y-1 hover:shadow-soft">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-coffee-caramel text-white">
                    <i data-lucide="mic" class="h-6 w-6"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-coffee-espresso">Tambah Feedback</h3>
                <p class="mt-2 text-sm leading-6 text-coffee-espresso/55">
                    Rekam komentar, rating, dan pengalaman pelanggan.
                </p>
            </a>

            <a href="{{ route('pelanggan.index') }}" class="card p-6 transition hover:-translate-y-1 hover:shadow-soft">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-coffee-bronze text-white">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-coffee-espresso">Data Pelanggan</h3>
                <p class="mt-2 text-sm leading-6 text-coffee-espresso/55">
                    Kelola profil pelanggan untuk kebutuhan transaksi dan feedback.
                </p>
            </a>
        </section>
    @endif
</div>

@if(session('staff.type') === 'admin')
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
                Chart.defaults.color = 'rgba(59, 39, 29, .58)';

                const salesCanvas = document.getElementById('salesChart');

                if (salesCanvas) {
                    const salesCtx = salesCanvas.getContext('2d');
                    const gradient = salesCtx.createLinearGradient(0, 0, 0, 340);

                    gradient.addColorStop(0, 'rgba(111, 78, 55, .88)');
                    gradient.addColorStop(1, 'rgba(199, 149, 91, .20)');

                    new Chart(salesCtx, {
                        type: 'bar',
                        data: {
                            labels: @json(collect($monthlySales ?? [])->pluck('name')->values()),
                            datasets: [{
                                label: 'Penjualan',
                                data: @json(collect($monthlySales ?? [])->pluck('sales')->values()),
                                backgroundColor: gradient,
                                hoverBackgroundColor: '#3B271D',
                                borderRadius: 14,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#241711',
                                    padding: 14,
                                    displayColors: false,
                                    cornerRadius: 14,
                                    titleFont: { size: 13, weight: '700' },
                                    bodyFont: { size: 13, weight: '700' }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(111, 78, 55, .08)', drawBorder: false },
                                    border: { display: false },
                                    ticks: { padding: 10 }
                                },
                                x: {
                                    grid: { display: false, drawBorder: false },
                                    border: { display: false },
                                    ticks: { padding: 10 }
                                }
                            }
                        }
                    });
                }

                const satisfactionCanvas = document.getElementById('satisfactionChart');

                if (satisfactionCanvas) {
                    const satisfactionCtx = satisfactionCanvas.getContext('2d');

                    new Chart(satisfactionCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json(collect($satisfaction ?? [])->pluck('name')->values()),
                            datasets: [{
                                data: @json(collect($satisfaction ?? [])->pluck('value')->values()),
                                backgroundColor: ['#6F4E37', '#C7955B', '#A15C38'],
                                borderColor: '#FDF9F3',
                                borderWidth: 5,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            cutout: '72%',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#241711',
                                    padding: 14,
                                    displayColors: false,
                                    cornerRadius: 14,
                                    callbacks: {
                                        label: function (context) {
                                            return context.label + ': ' + context.parsed + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endif
@endsection
