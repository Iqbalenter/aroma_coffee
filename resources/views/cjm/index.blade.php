@extends('layouts.app')

@section('title', 'Kualitatif Customer Journey')

@push('head')
<style>
    .cjm-hero {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background:
            radial-gradient(circle at 12% 12%, rgba(199, 149, 91, .22), transparent 28%),
            radial-gradient(circle at 88% 8%, rgba(111, 78, 55, .13), transparent 26%),
            linear-gradient(135deg, rgba(253, 249, 243, .96), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }

    .cjm-panel {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    .cjm-badge {
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

    .cjm-stat-card {
        border-radius: 1.35rem;
        background: rgba(255, 251, 245, .75);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.15rem;
    }

    .cjm-curve-wrapper {
        overflow-x: auto;
        padding-bottom: .35rem;
    }

    .cjm-curve-canvas {
        position: relative;
        min-width: 980px;
        height: 390px;
        border-radius: 1.6rem;
        background:
            linear-gradient(180deg, rgba(63, 125, 88, .045) 0 33.33%, transparent 33.33% 66.66%, rgba(180, 83, 60, .045) 66.66% 100%),
            rgba(255, 251, 245, .58);
        border: 1px solid rgba(111, 78, 55, .08);
    }

    .cjm-axis-line {
        position: absolute;
        left: 2.25rem;
        right: 2.25rem;
        top: 50%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(111, 78, 55, .18), transparent);
        z-index: 1;
    }

    .cjm-axis-label {
        position: absolute;
        left: 1.15rem;
        z-index: 3;
        font-size: .62rem;
        font-weight: 900;
        letter-spacing: .12em;
        text-transform: uppercase;
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }

    .cjm-axis-positive {
        top: 2.3rem;
        color: #3F7D58;
    }

    .cjm-axis-neutral {
        top: 10.4rem;
        color: #A7784D;
    }

    .cjm-axis-negative {
        bottom: 2.3rem;
        color: #B4533C;
    }

    .cjm-curve-svg {
        position: absolute;
        left: 2.25rem;
        right: 2.25rem;
        top: 3rem;
        width: calc(100% - 4.5rem);
        height: calc(100% - 5rem);
        z-index: 1;
        overflow: visible;
    }

    .cjm-curve-grid {
        position: absolute;
        inset: 3rem 2.25rem 2rem 2.25rem;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(var(--stage-count), minmax(0, 1fr));
        gap: 1rem;
    }

    .cjm-curve-slot {
        display: flex;
        min-width: 0;
    }

    .cjm-curve-slot.is-positive {
        align-items: flex-start;
    }

    .cjm-curve-slot.is-neutral {
        align-items: center;
    }

    .cjm-curve-slot.is-negative {
        align-items: flex-end;
    }

    .cjm-stage-card {
        width: 100%;
        min-height: 156px;
        border-radius: 1.35rem;
        background: rgba(255, 255, 255, .88);
        border: 1px solid rgba(111, 78, 55, .12);
        box-shadow: 0 14px 28px rgba(68, 42, 25, .08);
        padding: 1rem;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .cjm-stage-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 36px rgba(68, 42, 25, .12);
    }

    .cjm-stage-index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 1.85rem;
        min-width: 1.85rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 900;
        color: #fff;
        background: var(--tone);
    }

    .cjm-sentiment-pill {
        display: inline-flex;
        align-items: center;
        gap: .38rem;
        border-radius: 999px;
        padding: .35rem .65rem;
        font-size: .66rem;
        font-weight: 900;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--tone);
        background: var(--tone-bg);
        border: 1px solid var(--tone-border);
    }

    .cjm-icon-bubble {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.7rem;
        width: 2.7rem;
        border-radius: 1rem;
        color: var(--tone);
        background: var(--tone-bg);
        border: 1px solid var(--tone-border);
    }

    .cjm-mobile-timeline {
        position: relative;
        display: grid;
        gap: 1rem;
    }

    .cjm-mobile-timeline::before {
        content: "";
        position: absolute;
        left: 1.35rem;
        top: 1.2rem;
        bottom: 1.2rem;
        width: 2px;
        border-radius: 999px;
        background: linear-gradient(180deg, rgba(63, 125, 88, .45), rgba(199, 149, 91, .40), rgba(180, 83, 60, .42));
    }

    .cjm-mobile-item {
        position: relative;
        display: grid;
        grid-template-columns: 3rem minmax(0, 1fr);
        gap: .75rem;
    }

    .cjm-mobile-marker {
        z-index: 2;
        display: flex;
        height: 2.75rem;
        width: 2.75rem;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        color: #fff;
        background: var(--tone);
        box-shadow: 0 12px 24px rgba(68, 42, 25, .16);
    }

    .cjm-mobile-card,
    .cjm-detail-card {
        border-radius: 1.35rem;
        background: rgba(255, 255, 255, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07);
    }

    .cjm-detail-card {
        overflow: hidden;
    }

    .cjm-detail-topline {
        height: 5px;
        background: var(--tone);
    }

    .cjm-theme-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .42rem .72rem;
        font-size: .72rem;
        font-weight: 800;
        color: #6F4E37;
        background: rgba(199, 149, 91, .12);
        border: 1px solid rgba(199, 149, 91, .18);
    }

    .cjm-comment {
        border-radius: 1rem;
        background: rgba(248, 239, 227, .42);
        border: 1px solid rgba(111, 78, 55, .08);
        padding: .85rem;
    }
</style>
@endpush

@section('content')
@php
    $stages = collect($stageData ?? []);
    $stageCount = max($stages->count(), 1);

    $sentimentMeta = [
        'positif' => [
            'label' => 'Positif',
            'icon' => 'smile-plus',
            'tone' => '#3F7D58',
            'bg' => 'rgba(63, 125, 88, .11)',
            'border' => 'rgba(63, 125, 88, .24)',
            'slot' => 'is-positive',
            'y' => 22,
            'summary' => 'Pengalaman pelanggan berada pada titik kuat.',
        ],
        'netral' => [
            'label' => 'Netral',
            'icon' => 'meh',
            'tone' => '#A7784D',
            'bg' => 'rgba(199, 149, 91, .13)',
            'border' => 'rgba(199, 149, 91, .24)',
            'slot' => 'is-neutral',
            'y' => 50,
            'summary' => 'Pengalaman pelanggan masih stabil, belum terlalu kuat.',
        ],
        'negatif' => [
            'label' => 'Negatif',
            'icon' => 'frown',
            'tone' => '#B4533C',
            'bg' => 'rgba(180, 83, 60, .11)',
            'border' => 'rgba(180, 83, 60, .24)',
            'slot' => 'is-negative',
            'y' => 78,
            'summary' => 'Tahap ini perlu perhatian karena memuat keluhan.',
        ],
    ];

    $totalVoices = $stages->sum(fn ($data) => (int) data_get($data, 'count', 0));
    $totalPainPoints = $stages->sum(fn ($data) => (int) data_get($data, 'pain_points', 0));
    $activeStages = $stages->filter(fn ($data) => (int) data_get($data, 'count', 0) > 0)->count();

    $dominantPositiveStages = $stages->filter(function ($data) {
        return strtolower((string) data_get($data, 'journey.sentimen_dominan', 'netral')) === 'positif';
    })->count();

    $curvePoints = $stages->values()->map(function ($data, $index) use ($stageCount, $sentimentMeta) {
        $sentiment = strtolower((string) data_get($data, 'journey.sentimen_dominan', 'netral'));
        $meta = $sentimentMeta[$sentiment] ?? $sentimentMeta['netral'];

        return [
            'x' => $stageCount > 1 ? 8 + (($index * 84) / ($stageCount - 1)) : 50,
            'y' => $meta['y'],
            'color' => $meta['tone'],
        ];
    });

    $polyline = $curvePoints
        ->map(fn ($point) => number_format($point['x'], 2, '.', '') . ',' . number_format($point['y'], 2, '.', ''))
        ->implode(' ');
@endphp

<div class="space-y-8">
    <section class="cjm-hero p-6 md:p-8">
        <div class="relative grid gap-6 lg:grid-cols-[1.35fr_.9fr] lg:items-end">
            <div>
                <span class="cjm-badge">
                    <i data-lucide="map" class="h-4 w-4"></i>
                    Customer Journey Mapping
                </span>

                <h1 class="mt-5 font-serif text-4xl font-bold leading-tight text-coffee-espresso md:text-5xl">
                    Analisis Kualitatif Pengalaman Pelanggan
                </h1>

                <p class="mt-4 max-w-3xl text-sm leading-7 text-coffee-espresso/60 md:text-base">
                    Visualisasi ini membaca feedback pelanggan berdasarkan tahap perjalanan,
                    sentimen dominan, tema utama, pain point, dan voice of customer.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-2">
                <div class="cjm-stat-card">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">Total Suara</p>
                    <p class="mt-2 text-3xl font-extrabold text-coffee-espresso">{{ number_format($totalVoices, 0, ',', '.') }}</p>
                </div>

                <div class="cjm-stat-card">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">Stage Aktif</p>
                    <p class="mt-2 text-3xl font-extrabold text-coffee-espresso">{{ $activeStages }}/{{ $stageCount }}</p>
                </div>

                <div class="cjm-stat-card">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">Pain Point</p>
                    <p class="mt-2 text-3xl font-extrabold text-coffee-espresso">{{ number_format($totalPainPoints, 0, ',', '.') }}</p>
                </div>

                <div class="cjm-stat-card">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">Dominan Positif</p>
                    <p class="mt-2 text-3xl font-extrabold text-coffee-espresso">{{ $dominantPositiveStages }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cjm-panel p-5 md:p-7">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="cjm-badge">
                    <i data-lucide="activity" class="h-4 w-4"></i>
                    Emotional Curve
                </span>

                <h2 class="mt-4 font-serif text-3xl font-bold text-coffee-espresso">
                    Kurva Emosi Pelanggan
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-7 text-coffee-espresso/58">
                    Membaca perubahan sentimen pelanggan dari tahap awareness sampai loyalty.
                    Tampilan desktop memakai kurva horizontal; tampilan mobile memakai timeline vertikal.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach($sentimentMeta as $key => $meta)
                    <span class="cjm-sentiment-pill"
                          style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                        <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5"></i>
                        {{ $meta['label'] }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="hidden xl:block">
            <div class="cjm-curve-wrapper">
                <div class="cjm-curve-canvas">
                    <div class="cjm-axis-label cjm-axis-positive">Positif</div>
                    <div class="cjm-axis-label cjm-axis-neutral">Netral</div>
                    <div class="cjm-axis-label cjm-axis-negative">Negatif</div>

                    <div class="cjm-axis-line"></div>

                    <svg class="cjm-curve-svg" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="cjmCurveGradient" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#3F7D58" />
                                <stop offset="48%" stop-color="#C7955B" />
                                <stop offset="100%" stop-color="#B4533C" />
                            </linearGradient>
                        </defs>

                        <polyline
                            points="{{ $polyline }}"
                            fill="none"
                            stroke="url(#cjmCurveGradient)"
                            stroke-width="3.2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            vector-effect="non-scaling-stroke"
                        />

                        @foreach($curvePoints as $point)
                            <circle
                                cx="{{ number_format($point['x'], 2, '.', '') }}"
                                cy="{{ number_format($point['y'], 2, '.', '') }}"
                                r="2"
                                fill="{{ $point['color'] }}"
                                stroke="#FFF8EF"
                                stroke-width="1.1"
                                vector-effect="non-scaling-stroke"
                            />
                        @endforeach
                    </svg>

                    <div class="cjm-curve-grid" style="--stage-count: {{ $stageCount }};">
                        @foreach($stages as $key => $data)
                            @php
                                $sentiment = strtolower((string) data_get($data, 'journey.sentimen_dominan', 'netral'));
                                $meta = $sentimentMeta[$sentiment] ?? $sentimentMeta['netral'];
                                $title = data_get($data, 'meta.title', ucwords(str_replace('_', ' ', (string) $key)));
                                $desc = data_get($data, 'meta.desc', 'Tahapan perjalanan pelanggan');
                                $count = (int) data_get($data, 'count', 0);
                            @endphp

                            <div class="cjm-curve-slot {{ $meta['slot'] }}">
                                <article class="cjm-stage-card"
                                         style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                    <div class="mb-3 flex items-start justify-between gap-3">
                                        <span class="cjm-stage-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                                        <span class="cjm-sentiment-pill"
                                              style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                            {{ $meta['label'] }}
                                        </span>
                                    </div>

                                    <h3 class="text-sm font-extrabold uppercase tracking-[.08em] text-coffee-espresso">
                                        {{ $title }}
                                    </h3>

                                    <p class="mt-1 min-h-[2.35rem] text-xs leading-5 text-coffee-espresso/55">
                                        {{ $desc }}
                                    </p>

                                    <div class="mt-4 flex items-center justify-between gap-3">
                                        <div class="cjm-icon-bubble"
                                             style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                            <i data-lucide="{{ $meta['icon'] }}" class="h-5 w-5"></i>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-lg font-extrabold text-coffee-espresso">{{ $count }}</p>
                                            <p class="text-[10px] font-bold uppercase tracking-[.12em] text-coffee-espresso/40">Suara</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:hidden">
            <div class="cjm-mobile-timeline">
                @foreach($stages as $key => $data)
                    @php
                        $sentiment = strtolower((string) data_get($data, 'journey.sentimen_dominan', 'netral'));
                        $meta = $sentimentMeta[$sentiment] ?? $sentimentMeta['netral'];
                        $title = data_get($data, 'meta.title', ucwords(str_replace('_', ' ', (string) $key)));
                        $desc = data_get($data, 'meta.desc', 'Tahapan perjalanan pelanggan');
                        $count = (int) data_get($data, 'count', 0);
                    @endphp

                    <article class="cjm-mobile-item"
                             style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                        <div class="cjm-mobile-marker">
                            <i data-lucide="{{ $meta['icon'] }}" class="h-5 w-5"></i>
                        </div>

                        <div class="cjm-mobile-card p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">
                                        Stage {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </p>

                                    <h3 class="mt-1 text-base font-extrabold text-coffee-espresso">{{ $title }}</h3>
                                </div>

                                <span class="cjm-sentiment-pill"
                                      style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                    {{ $meta['label'] }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm leading-6 text-coffee-espresso/58">{{ $desc }}</p>

                            <div class="mt-4 rounded-2xl bg-coffee-cream/60 px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase tracking-[.12em] text-coffee-bronze">Jumlah Suara</span>
                                    <span class="text-lg font-extrabold text-coffee-espresso">{{ $count }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section>
        <div class="mb-5">
            <span class="cjm-badge">
                <i data-lucide="message-square-text" class="h-4 w-4"></i>
                Thematic & Verbatim Analysis
            </span>

            <h2 class="mt-4 font-serif text-3xl font-bold text-coffee-espresso">
                Detail Insight Tiap Tahap
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-7 text-coffee-espresso/58">
                Bagian ini menampilkan tema utama, pain point, dan kutipan suara pelanggan
                agar analisis tidak hanya berbasis angka.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 2xl:grid-cols-3">
            @foreach($stages as $key => $data)
                @php
                    $sentiment = strtolower((string) data_get($data, 'journey.sentimen_dominan', 'netral'));
                    $meta = $sentimentMeta[$sentiment] ?? $sentimentMeta['netral'];

                    $title = data_get($data, 'meta.title', ucwords(str_replace('_', ' ', (string) $key)));
                    $desc = data_get($data, 'meta.desc', 'Tahapan perjalanan pelanggan');
                    $count = (int) data_get($data, 'count', 0);
                    $painPoints = (int) data_get($data, 'pain_points', 0);

                    $themes = collect(data_get($data, 'top_themes', []));
                    $comments = collect(data_get($data, 'recent_comments', []));
                @endphp

                <article class="cjm-detail-card"
                         style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                    <div class="cjm-detail-topline"></div>

                    <div class="p-5">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">
                                    Stage {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </p>

                                <h3 class="mt-1 text-xl font-extrabold text-coffee-espresso">
                                    {{ $title }}
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-coffee-espresso/55">
                                    {{ $desc }}
                                </p>
                            </div>

                            <div class="cjm-icon-bubble shrink-0"
                                 style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                <i data-lucide="{{ $meta['icon'] }}" class="h-5 w-5"></i>
                            </div>
                        </div>

                        <div class="mb-5 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-coffee-cream/60 p-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.14em] text-coffee-bronze">Suara</p>
                                <p class="mt-1 text-2xl font-extrabold text-coffee-espresso">{{ $count }}</p>
                            </div>

                            <div class="rounded-2xl bg-coffee-cream/60 p-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.14em] text-coffee-bronze">Keluhan</p>
                                <p class="mt-1 text-2xl font-extrabold text-coffee-espresso">{{ $painPoints }}</p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">
                                    Tema Utama
                                </p>

                                <span class="cjm-sentiment-pill"
                                      style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                    {{ $meta['label'] }}
                                </span>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @forelse($themes as $tema => $jumlah)
                                    <span class="cjm-theme-chip">
                                        {{ $tema }} · {{ $jumlah }}
                                    </span>
                                @empty
                                    <span class="text-sm italic text-coffee-espresso/40">
                                        Belum ada tema yang terdeteksi.
                                    </span>
                                @endforelse
                            </div>
                        </div>

                        @if($painPoints > 0)
                            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50/70 p-4 text-sm leading-6 text-red-700">
                                <div class="flex gap-3">
                                    <i data-lucide="alert-triangle" class="mt-0.5 h-5 w-5 shrink-0"></i>
                                    <p>
                                        Ada <strong>{{ $painPoints }}</strong> feedback negatif pada tahap ini.
                                        Bagian ini sebaiknya diprioritaskan untuk evaluasi layanan.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="mb-5 rounded-2xl border border-coffee-mocha/10 bg-coffee-cream/50 p-4 text-sm leading-6 text-coffee-espresso/58">
                                <div class="flex gap-3">
                                    <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 shrink-0 text-[#3F7D58]"></i>
                                    <p>{{ $meta['summary'] }}</p>
                                </div>
                            </div>
                        @endif

                        <div>
                            <p class="mb-3 text-[10px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">
                                Voice of Customer
                            </p>

                            <div class="space-y-3">
                                @forelse($comments as $feedback)
                                    @php
                                        $feedbackSentiment = strtolower((string) data_get($feedback, 'sentimen', 'netral'));
                                        $feedbackMeta = $sentimentMeta[$feedbackSentiment] ?? $sentimentMeta['netral'];
                                        $comment = (string) data_get($feedback, 'komentar', '');
                                    @endphp

                                    <div class="cjm-comment">
                                        <div class="mb-2 flex items-center justify-between gap-3">
                                            <span class="text-[10px] font-extrabold uppercase tracking-[.14em] text-coffee-espresso/45">
                                                Verbatim
                                            </span>

                                            <span class="cjm-sentiment-pill"
                                                  style="--tone: {{ $feedbackMeta['tone'] }}; --tone-bg: {{ $feedbackMeta['bg'] }}; --tone-border: {{ $feedbackMeta['border'] }};">
                                                {{ $feedbackMeta['label'] }}
                                            </span>
                                        </div>

                                        <p class="text-sm italic leading-6 text-coffee-espresso/68">
                                            “{{ \Illuminate\Support\Str::limit($comment, 120) }}”
                                        </p>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-coffee-mocha/15 bg-white/50 p-4 text-sm italic text-coffee-espresso/40">
                                        Belum ada suara pelanggan pada tahap ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
