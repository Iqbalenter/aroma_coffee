@extends('layouts.app')

@section('title', 'Laporan')

@push('head')
<style>
    .report-page {
        color: #241711;
    }

    .report-hero {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background:
            radial-gradient(circle at 10% 10%, rgba(199, 149, 91, .24), transparent 28%),
            radial-gradient(circle at 92% 5%, rgba(111, 78, 55, .15), transparent 28%),
            linear-gradient(135deg, rgba(253, 249, 243, .98), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }

    .report-panel {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    .report-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        border-radius: 999px;
        padding: .45rem .85rem;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #6F4E37;
        background: rgba(199, 149, 91, .13);
        border: 1px solid rgba(199, 149, 91, .20);
    }

    .report-title {
        color: #3B271D;
        letter-spacing: -.02em;
    }

    .report-muted {
        color: rgba(36, 23, 17, .58);
    }

    .report-stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.35rem;
        background: rgba(255, 251, 245, .78);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.15rem;
    }

    .report-stat-card::after {
        content: "";
        position: absolute;
        right: -2.25rem;
        top: -2.25rem;
        width: 6.8rem;
        height: 6.8rem;
        border-radius: 999px;
        background: var(--tone-bg);
    }

    .report-icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.85rem;
        height: 2.85rem;
        border-radius: 1rem;
        color: var(--tone);
        background: var(--tone-bg);
        border: 1px solid var(--tone-border);
    }

    .report-pill {
        display: inline-flex;
        align-items: center;
        gap: .38rem;
        border-radius: 999px;
        padding: .36rem .68rem;
        font-size: .68rem;
        font-weight: 900;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--tone);
        background: var(--tone-bg);
        border: 1px solid var(--tone-border);
    }

    .report-chart-wrap {
        position: relative;
        min-height: 330px;
    }

    .report-mini-chart {
        position: relative;
        min-height: 270px;
    }

    .report-empty {
        display: flex;
        min-height: 240px;
        align-items: center;
        justify-content: center;
        border-radius: 1.5rem;
        background: rgba(248, 239, 227, .42);
        border: 1px dashed rgba(111, 78, 55, .22);
        text-align: center;
    }

    .report-list-item {
        border-radius: 1.25rem;
        background: rgba(255, 251, 245, .74);
        border: 1px solid rgba(111, 78, 55, .09);
        padding: 1rem;
    }

    .report-progress-track {
        height: .62rem;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(232, 214, 191, .62);
    }

    .report-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--tone);
    }

    .report-table-wrap {
        overflow-x: auto;
    }

    .report-table {
        min-width: 860px;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .report-table thead th {
        background: rgba(232, 214, 191, .45);
        color: rgba(59, 39, 29, .82);
        font-size: .7rem;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: .95rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(111, 78, 55, .10);
        white-space: nowrap;
    }

    .report-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid rgba(111, 78, 55, .08);
        color: rgba(36, 23, 17, .82);
        font-size: .875rem;
        vertical-align: top;
    }

    .report-table tbody tr:hover {
        background: rgba(248, 239, 227, .42);
    }

    .report-feedback-card {
        border-radius: 1.25rem;
        background: rgba(255, 251, 245, .78);
        border: 1px solid rgba(111, 78, 55, .09);
        padding: 1rem;
    }

    .report-rating {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        color: #C7955B;
        font-weight: 900;
    }

    .report-print-button {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        border-radius: 999px;
        padding: .78rem 1.1rem;
        background: linear-gradient(135deg, var(--caramel), var(--mocha));
        color: white;
        font-size: .875rem;
        font-weight: 800;
        box-shadow: 0 12px 24px rgba(59, 39, 29, .18);
        transition: all .2s ease;
    }

    .report-print-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(59, 39, 29, .23);
    }

    @media (max-width: 640px) {
        .report-hero,
        .report-panel {
            border-radius: 1.5rem;
        }

        .report-chart-wrap {
            min-height: 280px;
        }
    }

    @media print {
        aside,
        header,
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
        }

        main {
            padding: 0 !important;
            overflow: visible !important;
        }

        .report-hero,
        .report-panel,
        .report-stat-card {
            box-shadow: none !important;
            break-inside: avoid;
            background: white !important;
        }

        .report-page {
            color: #000 !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $rupiah = fn ($number) => 'Rp ' . number_format((float) $number, 0, ',', '.');

    $trend = collect($trendData ?? []);
    $payments = collect($paymentData ?? []);
    $segments = collect($segmentData ?? []);
    $products = collect($topProducts ?? []);
    $feedbacks = collect($recentFeedback ?? []);

    $rows = method_exists($transaksi, 'getCollection')
        ? $transaksi->getCollection()
        : collect($transaksi ?? []);

    $fallbackRevenue = $rows->sum(fn ($row) => (float) data_get($row, 'total_harga', 0));
    $fallbackTransactions = $trend->sum(fn ($row) => (int) data_get($row, 'trx', 0));

    $monthlyRevenue = (float) data_get($stats ?? [], 'monthly_revenue', $fallbackRevenue);
    $previousRevenue = (float) data_get($stats ?? [], 'previous_revenue', 0);
    $revenueGrowth = (float) data_get($stats ?? [], 'revenue_growth', 0);
    $monthlyTransactions = (int) data_get($stats ?? [], 'monthly_transactions', $fallbackTransactions);
    $avgOrderValue = (float) data_get($stats ?? [], 'avg_order_value', $monthlyTransactions > 0 ? $monthlyRevenue / $monthlyTransactions : 0);
    $uniqueCustomers = (int) data_get($stats ?? [], 'unique_customers', 0);
    $newCustomers = (int) data_get($stats ?? [], 'new_customers', 0);
    $feedbackCount = (int) data_get($stats ?? [], 'feedback_count', 0);
    $avgRating = (float) data_get($stats ?? [], 'avg_rating', 0);

    $peakWeek = $trend->sortByDesc(fn ($row) => (int) data_get($row, 'trx', 0))->first();
    $peakWeekName = data_get($peakWeek, 'name', '-');
    $peakWeekTransactions = (int) data_get($peakWeek, 'trx', 0);

    $maxProductQty = max(1, (int) $products->max(fn ($row) => (int) data_get($row, 'qty', 0)));
    $totalSegmentCustomers = max(1, (int) $segments->sum(fn ($row) => (int) data_get($row, 'count', 0)));

    $growthTone = $revenueGrowth >= 0 ? '#3F7D58' : '#B4533C';
    $growthBg = $revenueGrowth >= 0 ? 'rgba(63, 125, 88, .12)' : 'rgba(180, 83, 60, .12)';
    $growthBorder = $revenueGrowth >= 0 ? 'rgba(63, 125, 88, .24)' : 'rgba(180, 83, 60, .24)';
    $growthIcon = $revenueGrowth >= 0 ? 'trending-up' : 'trending-down';

    $statCards = [
        [
            'label' => 'Omzet Bulan Ini',
            'value' => $rupiah($monthlyRevenue),
            'desc' => 'Akumulasi nilai transaksi bulan berjalan',
            'icon' => 'wallet',
            'tone' => '#6F4E37',
            'bg' => 'rgba(111, 78, 55, .12)',
            'border' => 'rgba(111, 78, 55, .24)',
        ],
        [
            'label' => 'Total Transaksi',
            'value' => number_format($monthlyTransactions, 0, ',', '.'),
            'desc' => 'Jumlah transaksi pada periode laporan',
            'icon' => 'receipt',
            'tone' => '#C7955B',
            'bg' => 'rgba(199, 149, 91, .14)',
            'border' => 'rgba(199, 149, 91, .28)',
        ],
        [
            'label' => 'Rata-rata Order',
            'value' => $rupiah($avgOrderValue),
            'desc' => 'Nilai rata-rata pembelian per transaksi',
            'icon' => 'bar-chart-3',
            'tone' => '#3F7D58',
            'bg' => 'rgba(63, 125, 88, .12)',
            'border' => 'rgba(63, 125, 88, .24)',
        ],
        [
            'label' => 'Rating Feedback',
            'value' => number_format($avgRating, 1, ',', '.'),
            'desc' => 'Rata-rata rating dari feedback bulan ini',
            'icon' => 'star',
            'tone' => '#D4A853',
            'bg' => 'rgba(212, 168, 83, .15)',
            'border' => 'rgba(212, 168, 83, .28)',
        ],
    ];
@endphp

<div class="report-page space-y-8">
    <section class="report-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.95fr] xl:items-end">
            <div>
                <span class="report-badge">
                    <i data-lucide="file-bar-chart" class="h-4 w-4"></i>
                    Management Report
                </span>

                <h1 class="report-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">
                    Laporan Loyalitas & Transaksi
                </h1>

                <p class="report-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Ringkasan performa Aroma Coffee Bland pada periode
                    <strong>{{ now()->translatedFormat('F Y') }}</strong>.
                    Halaman ini menampilkan tren transaksi, omzet, metode pembayaran,
                    produk terlaris, segmentasi pelanggan, dan feedback terbaru.
                </p>

                <div class="no-print mt-6 flex flex-wrap gap-3">
                    <button type="button" onclick="window.print()" class="report-print-button">
                        <i data-lucide="printer" class="h-4 w-4"></i>
                        Cetak Laporan
                    </button>

                    <a href="{{ route('transaksi.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="receipt" class="h-4 w-4"></i>
                        Data Transaksi
                    </a>

                    <a href="{{ route('segmentasi.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="pie-chart" class="h-4 w-4"></i>
                        Segmentasi
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="report-stat-card"
                     style="--tone: {{ $growthTone }}; --tone-bg: {{ $growthBg }}; --tone-border: {{ $growthBorder }};">
                    <div class="relative z-10">
                        <div class="mb-3 report-icon-box"
                             style="--tone: {{ $growthTone }}; --tone-bg: {{ $growthBg }}; --tone-border: {{ $growthBorder }};">
                            <i data-lucide="{{ $growthIcon }}" class="h-5 w-5"></i>
                        </div>

                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Growth Omzet
                        </p>

                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ rtrim(rtrim(number_format($revenueGrowth, 1, ',', '.'), '0'), ',') }}%
                        </p>
                    </div>
                </div>

                <div class="report-stat-card"
                     style="--tone: #5B8EA8; --tone-bg: rgba(91, 142, 168, .12); --tone-border: rgba(91, 142, 168, .24);">
                    <div class="relative z-10">
                        <div class="mb-3 report-icon-box"
                             style="--tone: #5B8EA8; --tone-bg: rgba(91, 142, 168, .12); --tone-border: rgba(91, 142, 168, .24);">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>

                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Customer Unik
                        </p>

                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ number_format($uniqueCustomers, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="report-stat-card"
                     style="--tone: #C7955B; --tone-bg: rgba(199, 149, 91, .14); --tone-border: rgba(199, 149, 91, .28);">
                    <div class="relative z-10">
                        <div class="mb-3 report-icon-box"
                             style="--tone: #C7955B; --tone-bg: rgba(199, 149, 91, .14); --tone-border: rgba(199, 149, 91, .28);">
                            <i data-lucide="user-plus" class="h-5 w-5"></i>
                        </div>

                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Pelanggan Baru
                        </p>

                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ number_format($newCustomers, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="report-stat-card"
                     style="--tone: #3F7D58; --tone-bg: rgba(63, 125, 88, .12); --tone-border: rgba(63, 125, 88, .24);">
                    <div class="relative z-10">
                        <div class="mb-3 report-icon-box"
                             style="--tone: #3F7D58; --tone-bg: rgba(63, 125, 88, .12); --tone-border: rgba(63, 125, 88, .24);">
                            <i data-lucide="message-circle" class="h-5 w-5"></i>
                        </div>

                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Feedback
                        </p>

                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ number_format($feedbackCount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($statCards as $card)
            <article class="report-stat-card"
                     style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                <div class="relative z-10">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div class="report-icon-box"
                             style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                            <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                        </div>

                        <span class="report-pill"
                              style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                            Bulan Ini
                        </span>
                    </div>

                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                        {{ $card['label'] }}
                    </p>

                    <p class="mt-2 text-3xl font-extrabold tracking-tight text-[#3B271D]">
                        {{ $card['value'] }}
                    </p>

                    <p class="report-muted mt-3 text-sm leading-6">
                        {{ $card['desc'] }}
                    </p>
                </div>
            </article>
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.45fr_.8fr]">
        <div class="report-panel p-5 md:p-7">
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="report-badge">
                        <i data-lucide="activity" class="h-4 w-4"></i>
                        Transaction Trend
                    </span>

                    <h2 class="report-title mt-4 font-serif text-3xl font-bold">
                        Tren Transaksi Mingguan
                    </h2>

                    <p class="report-muted mt-2 text-sm leading-7">
                        Grafik ini menunjukkan jumlah transaksi dan nilai omzet pada setiap minggu bulan berjalan.
                    </p>
                </div>

                <div class="rounded-2xl bg-[#F8EFE3]/80 px-4 py-3">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                        Puncak Transaksi
                    </p>

                    <p class="mt-1 text-sm font-extrabold text-[#3B271D]">
                        {{ $peakWeekName }} · {{ number_format($peakWeekTransactions, 0, ',', '.') }} transaksi
                    </p>
                </div>
            </div>

            @if($trend->isNotEmpty())
                <div class="report-chart-wrap">
                    <canvas id="trendChart"></canvas>
                </div>
            @else
                <div class="report-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                            <i data-lucide="activity" class="h-7 w-7"></i>
                        </div>

                        <p class="font-bold text-[#3B271D]">Belum ada tren transaksi</p>
                        <p class="report-muted mt-2 text-sm leading-6">
                            Grafik akan muncul setelah transaksi bulan ini tersedia.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="report-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="report-badge">
                    <i data-lucide="credit-card" class="h-4 w-4"></i>
                    Payment Mix
                </span>

                <h2 class="report-title mt-4 font-serif text-3xl font-bold">
                    Metode Pembayaran
                </h2>

                <p class="report-muted mt-2 text-sm leading-7">
                    Distribusi metode pembayaran yang digunakan pelanggan.
                </p>
            </div>

            @if($payments->isNotEmpty())
                <div class="report-mini-chart">
                    <canvas id="paymentChart"></canvas>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach($payments as $payment)
                        @php
                            $count = (int) data_get($payment, 'count', 0);
                            $percent = $monthlyTransactions > 0 ? ($count / $monthlyTransactions) * 100 : 0;
                        @endphp

                        <div class="report-list-item">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <p class="font-extrabold text-[#3B271D]">
                                    {{ data_get($payment, 'name', 'Tidak dicatat') }}
                                </p>

                                <p class="text-sm font-extrabold text-[#6F4E37]">
                                    {{ number_format($count, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="report-progress-track">
                                <div class="report-progress-fill"
                                     style="--tone: #6F4E37; width: {{ min(100, max(0, $percent)) }}%;"></div>
                            </div>

                            <p class="report-muted mt-2 text-xs">
                                {{ rtrim(rtrim(number_format($percent, 1, ',', '.'), '0'), ',') }}% · {{ $rupiah(data_get($payment, 'total', 0)) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="report-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                            <i data-lucide="credit-card" class="h-7 w-7"></i>
                        </div>

                        <p class="font-bold text-[#3B271D]">Belum ada pembayaran</p>
                        <p class="report-muted mt-2 text-sm leading-6">
                            Data metode pembayaran akan muncul setelah transaksi dicatat.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        <div class="report-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="report-badge">
                    <i data-lucide="coffee" class="h-4 w-4"></i>
                    Best Seller
                </span>

                <h2 class="report-title mt-4 font-serif text-3xl font-bold">
                    Produk Terlaris
                </h2>

                <p class="report-muted mt-2 text-sm leading-7">
                    Produk dengan jumlah penjualan tertinggi pada bulan berjalan.
                </p>
            </div>

            <div class="space-y-4">
                @forelse($products as $product)
                    @php
                        $qty = (int) data_get($product, 'qty', 0);
                        $width = ($qty / $maxProductQty) * 100;
                    @endphp

                    <article class="report-list-item">
                        <div class="mb-3 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="truncate text-base font-extrabold text-[#3B271D]">
                                    {{ data_get($product, 'nama_produk', '-') }}
                                </p>

                                <p class="report-muted mt-1 text-xs font-semibold">
                                    {{ data_get($product, 'kategori', 'Menu') }}
                                </p>
                            </div>

                            <span class="report-pill shrink-0"
                                  style="--tone: #C7955B; --tone-bg: rgba(199, 149, 91, .14); --tone-border: rgba(199, 149, 91, .28);">
                                {{ $qty }} item
                            </span>
                        </div>

                        <div class="report-progress-track">
                            <div class="report-progress-fill"
                                 style="--tone: #C7955B; width: {{ min(100, max(0, $width)) }}%;"></div>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="report-muted">Estimasi omzet</span>
                            <span class="font-extrabold text-[#3B271D]">
                                {{ $rupiah(data_get($product, 'revenue', 0)) }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="report-empty">
                        <div class="max-w-xs px-6">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                <i data-lucide="coffee" class="h-7 w-7"></i>
                            </div>

                            <p class="font-bold text-[#3B271D]">Belum ada produk terjual</p>
                            <p class="report-muted mt-2 text-sm leading-6">
                                Produk terlaris akan muncul setelah ada detail transaksi.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="report-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="report-badge">
                    <i data-lucide="pie-chart" class="h-4 w-4"></i>
                    Customer Segment
                </span>

                <h2 class="report-title mt-4 font-serif text-3xl font-bold">
                    Distribusi Segmentasi
                </h2>

                <p class="report-muted mt-2 text-sm leading-7">
                    Gambaran status pelanggan untuk membaca basis loyalitas Aroma Coffee.
                </p>
            </div>

            @if($segments->isNotEmpty())
                <div class="report-mini-chart">
                    <canvas id="segmentChart"></canvas>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach($segments as $segment)
                        @php
                            $count = (int) data_get($segment, 'count', 0);
                            $percent = ($count / $totalSegmentCustomers) * 100;
                            $color = data_get($segment, 'color', '#6F4E37');
                        @endphp

                        <div class="report-list-item">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-2">
                                    <span class="h-3 w-3 shrink-0 rounded-full"
                                          style="background: {{ $color }}"></span>

                                    <p class="truncate font-extrabold text-[#3B271D]">
                                        {{ data_get($segment, 'label', '-') }}
                                    </p>
                                </div>

                                <p class="text-sm font-extrabold text-[#6F4E37]">
                                    {{ number_format($count, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="report-progress-track">
                                <div class="report-progress-fill"
                                     style="--tone: {{ $color }}; width: {{ min(100, max(0, $percent)) }}%;"></div>
                            </div>

                            <p class="report-muted mt-2 text-xs">
                                {{ rtrim(rtrim(number_format($percent, 1, ',', '.'), '0'), ',') }}% dari total pelanggan
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="report-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                            <i data-lucide="pie-chart" class="h-7 w-7"></i>
                        </div>

                        <p class="font-bold text-[#3B271D]">Belum ada data segmentasi</p>
                        <p class="report-muted mt-2 text-sm leading-6">
                            Segmentasi akan muncul setelah data pelanggan tersedia.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.2fr_.8fr]">
        <div class="report-panel overflow-hidden">
            <div class="border-b border-[#6F4E37]/10 p-5 md:p-7">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <span class="report-badge">
                            <i data-lucide="database" class="h-4 w-4"></i>
                            Raw Transaction Data
                        </span>

                        <h2 class="report-title mt-4 font-serif text-3xl font-bold">
                            Data Transaksi Bulan Ini
                        </h2>

                        <p class="report-muted mt-2 text-sm leading-7">
                            Daftar transaksi terbaru untuk validasi data laporan.
                        </p>
                    </div>

                    <span class="report-pill w-fit"
                          style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                        {{ number_format($rows->count(), 0, ',', '.') }} data tampil
                    </span>
                </div>
            </div>

            <div class="report-table-wrap">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Operator</th>
                            <th>Metode</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transaksi as $t)
                            <tr>
                                <td>
                                    <span class="font-extrabold text-[#3B271D]">
                                        #{{ $t->id_transaksi }}
                                    </span>
                                </td>

                                <td>
                                    <div class="font-bold text-[#3B271D]">
                                        {{ optional($t->tanggal_transaksi)->format('d M Y') }}
                                    </div>

                                    <div class="report-muted mt-1 text-xs">
                                        {{ optional($t->tanggal_transaksi)->format('H:i') }} WIB
                                    </div>
                                </td>

                                <td>
                                    <div class="font-bold text-[#3B271D]">
                                        {{ optional($t->pelanggan)->nama ?? 'Pelanggan' }}
                                    </div>

                                    <div class="report-muted mt-1 text-xs">
                                        ID Pelanggan: {{ $t->id_pelanggan }}
                                    </div>
                                </td>

                                <td>
                                    <div class="font-bold text-[#3B271D]">
                                        {{ optional($t->operator)->nama ?? 'Operator' }}
                                    </div>

                                    <div class="report-muted mt-1 text-xs">
                                        ID Operator: {{ $t->id_operator }}
                                    </div>
                                </td>

                                <td>
                                    <span class="report-pill"
                                          style="--tone: #A7784D; --tone-bg: rgba(199, 149, 91, .12); --tone-border: rgba(199, 149, 91, .24);">
                                        {{ $t->metode_bayar ?: 'Tidak dicatat' }}
                                    </span>
                                </td>

                                <td class="text-right">
                                    <span class="font-extrabold text-[#3B271D]">
                                        {{ $rupiah($t->total_harga) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="py-8 text-center">
                                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                            <i data-lucide="receipt" class="h-7 w-7"></i>
                                        </div>

                                        <p class="font-bold text-[#3B271D]">Belum ada transaksi bulan ini</p>
                                        <p class="report-muted mt-2 text-sm">
                                            Data transaksi akan muncul setelah operator mencatat penjualan.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($transaksi, 'links'))
                <div class="no-print border-t border-[#6F4E37]/10 p-5">
                    {{ $transaksi->links() }}
                </div>
            @endif
        </div>

        <div class="report-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="report-badge">
                    <i data-lucide="message-square-text" class="h-4 w-4"></i>
                    Latest Feedback
                </span>

                <h2 class="report-title mt-4 font-serif text-3xl font-bold">
                    Feedback Terbaru
                </h2>

                <p class="report-muted mt-2 text-sm leading-7">
                    Suara pelanggan terbaru pada bulan berjalan.
                </p>
            </div>

            <div class="space-y-4">
                @forelse($feedbacks as $feedback)
                    <article class="report-feedback-card">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-extrabold text-[#3B271D]">
                                    {{ optional($feedback->pelanggan)->nama ?? 'Pelanggan' }}
                                </p>

                                <p class="report-muted mt-1 truncate text-xs">
                                    {{ optional($feedback->produk)->nama_produk ?? 'Produk' }} ·
                                    {{ optional($feedback->tanggal_feedback)->format('d M Y H:i') }}
                                </p>
                            </div>

                            <span class="report-rating shrink-0">
                                <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                {{ $feedback->rating }}
                            </span>
                        </div>

                        <p class="text-sm italic leading-6 text-[#3B271D]/70">
                            “{{ \Illuminate\Support\Str::limit($feedback->komentar ?: 'Tidak ada komentar tertulis.', 120) }}”
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="report-pill"
                                  style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                                {{ $feedback->tahap_label ?? ucfirst((string) $feedback->tahap_journey) }}
                            </span>

                            <span class="report-pill"
                                  style="--tone: #3F7D58; --tone-bg: rgba(63, 125, 88, .12); --tone-border: rgba(63, 125, 88, .24);">
                                {{ ucfirst((string) $feedback->status) }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="report-empty">
                        <div class="max-w-xs px-6">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                <i data-lucide="message-circle" class="h-7 w-7"></i>
                            </div>

                            <p class="font-bold text-[#3B271D]">Belum ada feedback</p>
                            <p class="report-muted mt-2 text-sm leading-6">
                                Feedback terbaru akan tampil setelah data pengalaman pelanggan dicatat.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }

        if (typeof Chart === 'undefined') {
            return;
        }

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = 'rgba(59, 39, 29, .62)';

        const trendData = @json($trend->values());
        const paymentData = @json($payments->values());
        const segmentData = @json($segments->values());

        const rupiah = function (value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value || 0);
        };

        const trendCanvas = document.getElementById('trendChart');

        if (trendCanvas && trendData.length) {
            const ctx = trendCanvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 330);

            gradient.addColorStop(0, 'rgba(111, 78, 55, .85)');
            gradient.addColorStop(1, 'rgba(199, 149, 91, .22)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: trendData.map(item => item.name),
                    datasets: [
                        {
                            label: 'Transaksi',
                            data: trendData.map(item => Number(item.trx || 0)),
                            backgroundColor: gradient,
                            borderRadius: 14,
                            borderSkipped: false,
                            yAxisID: 'y'
                        },
                        {
                            type: 'line',
                            label: 'Omzet',
                            data: trendData.map(item => Number(item.revenue || 0)),
                            borderColor: '#C7955B',
                            backgroundColor: 'rgba(199, 149, 91, .14)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: .35,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                boxHeight: 8,
                                font: {
                                    weight: '700'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#241711',
                            padding: 14,
                            cornerRadius: 14,
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.label === 'Omzet') {
                                        return 'Omzet: ' + rupiah(context.parsed.y);
                                    }

                                    return 'Transaksi: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(111, 78, 55, .08)',
                                drawBorder: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                precision: 0,
                                padding: 10
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                padding: 10,
                                callback: function (value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    }

                                    if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    }

                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                padding: 10
                            }
                        }
                    }
                }
            });
        }

        const paymentCanvas = document.getElementById('paymentChart');

        if (paymentCanvas && paymentData.length) {
            new Chart(paymentCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: paymentData.map(item => item.name),
                    datasets: [{
                        data: paymentData.map(item => Number(item.count || 0)),
                        backgroundColor: ['#6F4E37', '#C7955B', '#A7784D', '#3F7D58', '#8A8077'],
                        borderColor: '#FDF9F3',
                        borderWidth: 5,
                        hoverOffset: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#241711',
                            padding: 14,
                            displayColors: false,
                            cornerRadius: 14
                        }
                    }
                }
            });
        }

        const segmentCanvas = document.getElementById('segmentChart');

        if (segmentCanvas && segmentData.length) {
            new Chart(segmentCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: segmentData.map(item => item.label),
                    datasets: [{
                        data: segmentData.map(item => Number(item.count || 0)),
                        backgroundColor: segmentData.map(item => item.color || '#6F4E37'),
                        borderColor: '#FDF9F3',
                        borderWidth: 5,
                        hoverOffset: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#241711',
                            padding: 14,
                            displayColors: false,
                            cornerRadius: 14
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
