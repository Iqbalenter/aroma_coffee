@extends('layouts.app')

@section('title', 'Segmentasi Pelanggan')

@push('head')
<style>
    .seg-page {
        color: #241711;
    }

    .seg-hero {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background:
            radial-gradient(circle at 10% 8%, rgba(199, 149, 91, .22), transparent 28%),
            radial-gradient(circle at 90% 0%, rgba(111, 78, 55, .14), transparent 26%),
            linear-gradient(135deg, rgba(253, 249, 243, .97), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }

    .seg-panel {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    .seg-badge {
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

    .seg-title {
        color: #3B271D;
        letter-spacing: -.02em;
    }

    .seg-muted {
        color: rgba(36, 23, 17, .58);
    }

    .seg-stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.35rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.15rem;
    }

    .seg-stat-card::after {
        content: "";
        position: absolute;
        right: -2.25rem;
        top: -2.25rem;
        width: 6.5rem;
        height: 6.5rem;
        border-radius: 999px;
        background: rgba(199, 149, 91, .12);
    }

    .seg-icon-box {
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

    .seg-pill {
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

    .seg-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.55rem;
        background: rgba(255, 255, 255, .82);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .seg-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 38px rgba(68, 42, 25, .12);
    }

    .seg-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 5px;
        background: var(--tone);
    }

    .seg-progress-track {
        height: .65rem;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(232, 214, 191, .62);
    }

    .seg-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--tone);
        transition: width .35s ease;
    }

    .seg-chart-wrap {
        position: relative;
        min-height: 310px;
    }

    .seg-empty-chart {
        display: flex;
        min-height: 290px;
        align-items: center;
        justify-content: center;
        border-radius: 1.5rem;
        background: rgba(248, 239, 227, .42);
        border: 1px dashed rgba(111, 78, 55, .22);
        text-align: center;
    }

    .seg-legend-item {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: .75rem;
        border-radius: 1.15rem;
        padding: .85rem;
        background: rgba(255, 251, 245, .72);
        border: 1px solid rgba(111, 78, 55, .08);
    }

    .seg-dot {
        width: .8rem;
        height: .8rem;
        border-radius: 999px;
        background: var(--tone);
        box-shadow: 0 0 0 4px var(--tone-bg);
    }

    .seg-pipeline {
        position: relative;
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
    }

    .seg-pipeline::before {
        content: "";
        position: absolute;
        left: 2rem;
        right: 2rem;
        top: 2rem;
        height: 2px;
        border-radius: 999px;
        background: linear-gradient(90deg, #8DB6C9, #6F4E37, #C7955B, #D4A853, #9E9E9E);
        opacity: .35;
    }

    .seg-pipeline-item {
        position: relative;
        z-index: 2;
        min-width: 0;
    }

    .seg-pipeline-marker {
        display: flex;
        width: 4rem;
        height: 4rem;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        border-radius: 1.35rem;
        color: white;
        background: var(--tone);
        box-shadow: 0 14px 30px rgba(68, 42, 25, .16);
    }

    .seg-pipeline-card {
        min-height: 9.5rem;
        border-radius: 1.35rem;
        background: rgba(255, 255, 255, .78);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1rem;
        text-align: center;
    }

    .seg-action-card {
        border-radius: 1.35rem;
        background: rgba(255, 255, 255, .78);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.1rem;
    }

    .seg-action-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: .85rem;
        color: white;
        background: var(--tone);
        font-size: .78rem;
        font-weight: 900;
    }

    .seg-rule {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        border-radius: 1.15rem;
        padding: .95rem;
        background: rgba(248, 239, 227, .48);
        border: 1px solid rgba(111, 78, 55, .08);
    }

    @media (max-width: 1279px) {
        .seg-pipeline {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .seg-pipeline::before {
            left: 2rem;
            right: auto;
            top: 1.5rem;
            bottom: 1.5rem;
            width: 2px;
            height: auto;
            background: linear-gradient(180deg, #8DB6C9, #6F4E37, #C7955B, #D4A853, #9E9E9E);
        }

        .seg-pipeline-item {
            display: grid;
            grid-template-columns: 4rem minmax(0, 1fr);
            gap: 1rem;
            align-items: stretch;
        }

        .seg-pipeline-marker {
            margin: 0;
        }

        .seg-pipeline-card {
            min-height: auto;
            text-align: left;
        }
    }

    @media (max-width: 640px) {
        .seg-hero,
        .seg-panel {
            border-radius: 1.5rem;
        }

        .seg-pipeline-item {
            grid-template-columns: 3.4rem minmax(0, 1fr);
            gap: .8rem;
        }

        .seg-pipeline::before {
            left: 1.7rem;
        }

        .seg-pipeline-marker {
            width: 3.4rem;
            height: 3.4rem;
            border-radius: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $segmentCollection = collect($segments ?? []);

    $defaultUi = [
        'icon' => 'users',
        'tone' => '#6F4E37',
        'bg' => 'rgba(111, 78, 55, .11)',
        'border' => 'rgba(111, 78, 55, .22)',
        'level' => 'General',
        'rule' => 'Segmentasi berdasarkan pola transaksi dan feedback.',
        'action' => 'Pantau perilaku pelanggan dan sesuaikan pendekatan layanan.',
    ];

    $segmentUi = [
        'baru' => [
            'icon' => 'user-plus',
            'tone' => '#5B8EA8',
            'bg' => 'rgba(91, 142, 168, .12)',
            'border' => 'rgba(91, 142, 168, .25)',
            'level' => 'Awal Relasi',
            'rule' => 'Pelanggan baru atau baru memiliki transaksi pertama.',
            'action' => 'Berikan pengalaman awal yang hangat, edukasi menu favorit, dan dorong transaksi berikutnya.',
        ],
        'aktif' => [
            'icon' => 'activity',
            'tone' => '#3F7D58',
            'bg' => 'rgba(63, 125, 88, .12)',
            'border' => 'rgba(63, 125, 88, .25)',
            'level' => 'Repeat Customer',
            'rule' => 'Pelanggan sering bertransaksi tetapi belum masuk kategori loyal.',
            'action' => 'Jaga konsistensi layanan, tawarkan promo ringan, dan kumpulkan feedback lebih detail.',
        ],
        'potensial_loyal' => [
            'icon' => 'trending-up',
            'tone' => '#C7955B',
            'bg' => 'rgba(199, 149, 91, .14)',
            'border' => 'rgba(199, 149, 91, .28)',
            'level' => 'Menuju Loyal',
            'rule' => 'Frekuensi transaksi dan rating mulai menunjukkan kecenderungan loyal.',
            'action' => 'Berikan treatment personal, rekomendasi menu, dan program reward bertahap.',
        ],
        'loyal' => [
            'icon' => 'heart-handshake',
            'tone' => '#D4A853',
            'bg' => 'rgba(212, 168, 83, .15)',
            'border' => 'rgba(212, 168, 83, .30)',
            'level' => 'Brand Advocate',
            'rule' => 'Pelanggan setia dengan repeat purchase dan rating tinggi.',
            'action' => 'Pertahankan dengan loyalty reward, apresiasi pelanggan, dan ajakan referral.',
        ],
        'tidak_aktif' => [
            'icon' => 'user-x',
            'tone' => '#8A8077',
            'bg' => 'rgba(138, 128, 119, .13)',
            'border' => 'rgba(138, 128, 119, .25)',
            'level' => 'Perlu Reaktivasi',
            'rule' => 'Pelanggan lama tidak melakukan transaksi.',
            'action' => 'Lakukan pendekatan reaktivasi melalui promo comeback, survei alasan berhenti, atau reminder.',
        ],
    ];

    $totalCustomers = $segmentCollection->sum(fn ($seg) => (int) data_get($seg, 'count', 0));
    $activeSegments = $segmentCollection->filter(fn ($seg) => (int) data_get($seg, 'count', 0) > 0)->count();

    $topSegmentKey = $segmentCollection
        ->sortByDesc(fn ($seg) => (int) data_get($seg, 'count', 0))
        ->keys()
        ->first();

    $topSegment = $topSegmentKey ? $segmentCollection->get($topSegmentKey) : null;
    $topSegmentLabel = $topSegment && $totalCustomers > 0 ? data_get($topSegment, 'label', '-') : 'Belum ada data';
    $topSegmentCount = $topSegment && $totalCustomers > 0 ? (int) data_get($topSegment, 'count', 0) : 0;

    $loyalCount = (int) data_get($segments, 'loyal.count', 0);
    $inactiveCount = (int) data_get($segments, 'tidak_aktif.count', 0);

    $loyalPercent = $totalCustomers > 0 ? round(($loyalCount / $totalCustomers) * 100, 1) : 0;
    $inactivePercent = $totalCustomers > 0 ? round(($inactiveCount / $totalCustomers) * 100, 1) : 0;

    $chartPayload = $segmentCollection
        ->map(function ($seg, $key) use ($segmentUi, $defaultUi) {
            $ui = $segmentUi[$key] ?? $defaultUi;

            return [
                'key' => $key,
                'name' => data_get($seg, 'label', ucwords(str_replace('_', ' ', (string) $key))),
                'value' => (int) data_get($seg, 'count', 0),
                'color' => $ui['tone'],
            ];
        })
        ->filter(fn ($item) => (int) $item['value'] > 0)
        ->values();

    $pipelineOrder = ['baru', 'aktif', 'potensial_loyal', 'loyal', 'tidak_aktif'];
@endphp

<div class="seg-page space-y-8">
    <section class="seg-hero p-6 md:p-8">
        <div class="relative grid gap-6 xl:grid-cols-[1.35fr_.95fr] xl:items-end">
            <div>
                <span class="seg-badge">
                    <i data-lucide="brain-circuit" class="h-4 w-4"></i>
                    Segmentasi Pelanggan
                </span>

                <h1 class="seg-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">
                    Membaca Tingkat Loyalitas Pelanggan Aroma Coffee
                </h1>

                <p class="seg-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Halaman ini mengelompokkan pelanggan berdasarkan status relasi dengan brand:
                    baru, aktif, potensial loyal, loyal, dan tidak aktif. Tujuannya agar strategi
                    layanan dan promosi bisa lebih tepat sasaran.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('pelanggan.index') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-coffee-caramel to-coffee-mocha px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        Data Pelanggan
                    </a>

                    <a href="{{ route('cjm.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="map" class="h-4 w-4"></i>
                        Journey Map
                    </a>

                    <a href="{{ route('laporan.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                        Laporan
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="seg-stat-card">
                    <div class="relative z-10">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Total Pelanggan
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ number_format($totalCustomers, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="seg-stat-card">
                    <div class="relative z-10">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Segmen Aktif
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ $activeSegments }}/{{ max($segmentCollection->count(), 1) }}
                        </p>
                    </div>
                </div>

                <div class="seg-stat-card">
                    <div class="relative z-10">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Loyal
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ rtrim(rtrim(number_format($loyalPercent, 1, ',', '.'), '0'), ',') }}%
                        </p>
                    </div>
                </div>

                <div class="seg-stat-card">
                    <div class="relative z-10">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Tidak Aktif
                        </p>
                        <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                            {{ rtrim(rtrim(number_format($inactivePercent, 1, ',', '.'), '0'), ',') }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[.92fr_1.08fr]">
        <div class="seg-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="seg-badge">
                    <i data-lucide="pie-chart" class="h-4 w-4"></i>
                    Distribusi
                </span>

                <h2 class="seg-title mt-4 font-serif text-3xl font-bold">
                    Komposisi Segmen
                </h2>

                <p class="seg-muted mt-2 text-sm leading-7">
                    Persentase pelanggan pada setiap kategori segmentasi.
                </p>
            </div>

            @if($totalCustomers > 0)
                <div class="seg-chart-wrap">
                    <canvas id="segmentChart"></canvas>
                </div>
            @else
                <div class="seg-empty-chart">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                            <i data-lucide="pie-chart" class="h-7 w-7"></i>
                        </div>

                        <p class="font-bold text-[#3B271D]">Belum ada data segmentasi</p>
                        <p class="seg-muted mt-2 text-sm leading-6">
                            Data akan tampil setelah pelanggan memiliki status segmentasi.
                        </p>
                    </div>
                </div>
            @endif

            <div class="mt-5 space-y-3">
                @foreach($segmentCollection as $key => $seg)
                    @php
                        $ui = $segmentUi[$key] ?? $defaultUi;
                        $count = (int) data_get($seg, 'count', 0);
                        $percent = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0;
                    @endphp

                    <div class="seg-legend-item"
                         style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                        <span class="seg-dot"></span>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-extrabold text-[#3B271D]">
                                {{ data_get($seg, 'label', ucwords(str_replace('_', ' ', (string) $key))) }}
                            </p>
                            <p class="seg-muted truncate text-xs">
                                {{ $percent }}% dari total pelanggan
                            </p>
                        </div>

                        <p class="text-sm font-extrabold text-[#3B271D]">
                            {{ number_format($count, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="seg-panel p-5 md:p-7">
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="seg-badge">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        Segment Cards
                    </span>

                    <h2 class="seg-title mt-4 font-serif text-3xl font-bold">
                        Ringkasan Tiap Segmen
                    </h2>

                    <p class="seg-muted mt-2 text-sm leading-7">
                        Jumlah pelanggan, proporsi, dan karakter umum per kategori.
                    </p>
                </div>

                <div class="rounded-2xl bg-[#F8EFE3]/80 px-4 py-3">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                        Segmen Terbesar
                    </p>
                    <p class="mt-1 text-sm font-extrabold text-[#3B271D]">
                        {{ $topSegmentLabel }} · {{ number_format($topSegmentCount, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @foreach($segmentCollection as $key => $seg)
                    @php
                        $ui = $segmentUi[$key] ?? $defaultUi;
                        $count = (int) data_get($seg, 'count', 0);
                        $percent = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0;
                    @endphp

                    <article class="seg-card p-5"
                             style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div class="seg-icon-box shrink-0"
                                 style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                                <i data-lucide="{{ $ui['icon'] }}" class="h-5 w-5"></i>
                            </div>

                            <span class="seg-pill"
                                  style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                                {{ $ui['level'] }}
                            </span>
                        </div>

                        <h3 class="text-xl font-extrabold text-[#3B271D]">
                            {{ data_get($seg, 'label', ucwords(str_replace('_', ' ', (string) $key))) }}
                        </h3>

                        <p class="seg-muted mt-2 min-h-[3rem] text-sm leading-6">
                            {{ data_get($seg, 'desc', $ui['rule']) }}
                        </p>

                        <div class="mt-5 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-4xl font-extrabold tracking-tight text-[#3B271D]">
                                    {{ number_format($count, 0, ',', '.') }}
                                </p>
                                <p class="mt-1 text-xs font-bold uppercase tracking-[.14em] text-[#A7784D]">
                                    Orang
                                </p>
                            </div>

                            <p class="text-right text-2xl font-extrabold" style="color: {{ $ui['tone'] }}">
                                {{ rtrim(rtrim(number_format($percent, 1, ',', '.'), '0'), ',') }}%
                            </p>
                        </div>

                        <div class="mt-5">
                            <div class="seg-progress-track">
                                <div class="seg-progress-fill" style="width: {{ $percent }}%;"></div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="seg-panel p-5 md:p-7">
        <div class="mb-7">
            <span class="seg-badge">
                <i data-lucide="route" class="h-4 w-4"></i>
                Loyalty Path
            </span>

            <h2 class="seg-title mt-4 font-serif text-3xl font-bold">
                Alur Perkembangan Loyalitas
            </h2>

            <p class="seg-muted mt-2 max-w-3xl text-sm leading-7">
                Segmentasi ini dapat dibaca sebagai perjalanan pelanggan dari tahap awal,
                aktif, potensial loyal, loyal, hingga risiko tidak aktif.
            </p>
        </div>

        <div class="seg-pipeline">
            @foreach($pipelineOrder as $key)
                @php
                    $seg = $segmentCollection->get($key, []);
                    $ui = $segmentUi[$key] ?? $defaultUi;
                    $count = (int) data_get($seg, 'count', 0);
                    $percent = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100, 1) : 0;
                @endphp

                <article class="seg-pipeline-item"
                         style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                    <div class="seg-pipeline-marker">
                        <i data-lucide="{{ $ui['icon'] }}" class="h-6 w-6"></i>
                    </div>

                    <div class="seg-pipeline-card">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                            Stage {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </p>

                        <h3 class="mt-2 font-extrabold text-[#3B271D]">
                            {{ data_get($seg, 'label', ucwords(str_replace('_', ' ', $key))) }}
                        </h3>

                        <p class="seg-muted mt-2 text-xs leading-5">
                            {{ $ui['rule'] }}
                        </p>

                        <div class="mt-4 flex items-center justify-between gap-3 rounded-2xl bg-[#F8EFE3]/70 px-3 py-2">
                            <span class="text-xs font-bold text-[#6F4E37]">{{ $count }} orang</span>
                            <span class="text-xs font-extrabold" style="color: {{ $ui['tone'] }}">{{ $percent }}%</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        <div class="seg-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="seg-badge">
                    <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                    Aturan Segmentasi
                </span>

                <h2 class="seg-title mt-4 font-serif text-3xl font-bold">
                    Dasar Pembacaan Segmen
                </h2>

                <p class="seg-muted mt-2 text-sm leading-7">
                    Gunakan bagian ini untuk menjelaskan logika segmentasi pada presentasi skripsi.
                </p>
            </div>

            <div class="space-y-3">
                @foreach($pipelineOrder as $key)
                    @php
                        $seg = $segmentCollection->get($key, []);
                        $ui = $segmentUi[$key] ?? $defaultUi;
                    @endphp

                    <div class="seg-rule"
                         style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                        <div class="seg-icon-box shrink-0"
                             style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                            <i data-lucide="{{ $ui['icon'] }}" class="h-5 w-5"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="font-extrabold text-[#3B271D]">
                                {{ data_get($seg, 'label', ucwords(str_replace('_', ' ', $key))) }}
                            </p>

                            <p class="seg-muted mt-1 text-sm leading-6">
                                {{ $ui['rule'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="seg-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="seg-badge">
                    <i data-lucide="lightbulb" class="h-4 w-4"></i>
                    Rekomendasi
                </span>

                <h2 class="seg-title mt-4 font-serif text-3xl font-bold">
                    Strategi Tindak Lanjut
                </h2>

                <p class="seg-muted mt-2 text-sm leading-7">
                    Setiap segmen memerlukan pendekatan layanan dan promosi yang berbeda.
                </p>
            </div>

            <div class="space-y-3">
                @foreach($pipelineOrder as $key)
                    @php
                        $seg = $segmentCollection->get($key, []);
                        $ui = $segmentUi[$key] ?? $defaultUi;
                        $count = (int) data_get($seg, 'count', 0);
                    @endphp

                    <article class="seg-action-card"
                             style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                        <div class="flex items-start gap-4">
                            <span class="seg-action-number">{{ $loop->iteration }}</span>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <h3 class="font-extrabold text-[#3B271D]">
                                        {{ data_get($seg, 'label', ucwords(str_replace('_', ' ', $key))) }}
                                    </h3>

                                    <span class="seg-pill w-fit"
                                          style="--tone: {{ $ui['tone'] }}; --tone-bg: {{ $ui['bg'] }}; --tone-border: {{ $ui['border'] }};">
                                        {{ $count }} pelanggan
                                    </span>
                                </div>

                                <p class="seg-muted mt-2 text-sm leading-6">
                                    {{ $ui['action'] }}
                                </p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }

        const chartData = @json($chartPayload);

        if (!chartData.length) {
            return;
        }

        const canvas = document.getElementById('segmentChart');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = 'rgba(59, 39, 29, .62)';

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: chartData.map(item => item.name),
                datasets: [{
                    data: chartData.map(item => item.value),
                    backgroundColor: chartData.map(item => item.color),
                    borderColor: '#FDF9F3',
                    borderWidth: 5,
                    hoverOffset: 8
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
                        cornerRadius: 14,
                        titleFont: {
                            size: 13,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 13,
                            weight: '700'
                        },
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((sum, value) => sum + Number(value), 0);
                                const value = Number(context.parsed);
                                const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;

                                return `${context.label}: ${value} pelanggan (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
