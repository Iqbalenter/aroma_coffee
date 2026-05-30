@extends('layouts.app')

@section('title', 'Feedback')

@push('head')
<style>
    /* Styling Dasar & Hero */
    .feedback-page { color: #241711; }
    .feedback-hero {
        position: relative; overflow: hidden; border-radius: 2rem;
        background: radial-gradient(circle at 10% 8%, rgba(199, 149, 91, .24), transparent 28%),
                    radial-gradient(circle at 90% 0%, rgba(111, 78, 55, .14), transparent 26%),
                    linear-gradient(135deg, rgba(253, 249, 243, .98), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }

    /* Panel & Cards */
    .feedback-panel {
        position: relative; overflow: hidden; border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }
    .feedback-stat-card {
        position: relative; overflow: hidden; border-radius: 1.35rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.15rem;
    }
    .feedback-stat-card::after {
        content: ""; position: absolute; right: -2.25rem; top: -2.25rem;
        width: 6.5rem; height: 6.5rem; border-radius: 999px; background: var(--tone-bg);
    }
    .feedback-stage-card, .feedback-list-card {
        border-radius: 1.25rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .09);
        padding: 1rem;
    }
    .feedback-mobile-card {
        border-radius: 1.35rem; background: rgba(255, 255, 255, .82);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07);
        padding: 1rem;
    }

    /* Badges, Pills & Icons */
    .feedback-badge {
        display: inline-flex; align-items: center; gap: .5rem; border-radius: 999px;
        padding: .45rem .85rem; font-size: .68rem; font-weight: 800; letter-spacing: .12em;
        text-transform: uppercase; color: #6F4E37;
        background: rgba(199, 149, 91, .13); border: 1px solid rgba(199, 149, 91, .20);
    }
    .feedback-pill {
        display: inline-flex; align-items: center; gap: .38rem; border-radius: 999px;
        padding: .36rem .68rem; font-size: .68rem; font-weight: 900; letter-spacing: .06em;
        text-transform: uppercase; color: var(--tone);
        background: var(--tone-bg); border: 1px solid var(--tone-border);
    }
    .feedback-icon-box {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2.85rem; height: 2.85rem; border-radius: 1rem;
        color: var(--tone); background: var(--tone-bg); border: 1px solid var(--tone-border);
    }

    /* Typography */
    .feedback-title { color: #3B271D; letter-spacing: -.02em; }
    .feedback-muted { color: rgba(36, 23, 17, .58); }
    .feedback-rating { display: inline-flex; align-items: center; gap: .25rem; color: #C7955B; font-weight: 900; }

    /* Progress & Charts */
    .feedback-chart-wrap { position: relative; min-height: 280px; }
    .feedback-progress-track {
        height: .62rem; overflow: hidden; border-radius: 999px;
        background: rgba(232, 214, 191, .62);
    }
    .feedback-progress-fill { height: 100%; border-radius: 999px; background: var(--tone); }

    /* Tables */
    .feedback-table-wrap { overflow-x: auto; }
    .feedback-table { min-width: 980px; width: 100%; border-collapse: separate; border-spacing: 0; }
    .feedback-table thead th {
        background: rgba(232, 214, 191, .45); color: rgba(59, 39, 29, .82);
        font-size: .7rem; font-weight: 900; letter-spacing: .08em; text-transform: uppercase;
        padding: .95rem 1rem; text-align: left; border-bottom: 1px solid rgba(111, 78, 55, .10);
        white-space: nowrap;
    }
    .feedback-table tbody td {
        padding: 1rem; border-bottom: 1px solid rgba(111, 78, 55, .08);
        color: rgba(36, 23, 17, .82); font-size: .875rem; vertical-align: top;
    }
    .feedback-table tbody tr:hover { background: rgba(248, 239, 227, .42); }

    /* Actions & Dialogs */
    .feedback-action {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2.35rem; height: 2.35rem; border-radius: .9rem;
        border: 1px solid rgba(111, 78, 55, .10); background: rgba(255, 255, 255, .72);
        color: #6F4E37; transition: all .18s ease;
    }
    .feedback-action:hover { background: #3B271D; color: white; }
    .feedback-danger:hover { background: #B4533C; color: white; }

    .feedback-empty {
        display: flex; min-height: 240px; align-items: center; justify-content: center;
        border-radius: 1.5rem; background: rgba(248, 239, 227, .42);
        border: 1px dashed rgba(111, 78, 55, .22); text-align: center;
    }
    .feedback-dialog {
        width: min(760px, calc(100% - 2rem)); border: 0; border-radius: 1.7rem;
        padding: 0; background: transparent;
    }
    .feedback-dialog::backdrop { background: rgba(36, 23, 17, .55); backdrop-filter: blur(6px); }
    .feedback-dialog-card {
        background: #FDF9F3; border: 1px solid rgba(111, 78, 55, .12);
        border-radius: 1.7rem; box-shadow: 0 24px 60px rgba(36, 23, 17, .28); overflow: hidden;
    }

    @media (max-width: 640px) {
        .feedback-hero, .feedback-panel { border-radius: 1.5rem; }
        .feedback-chart-wrap { min-height: 240px; }
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = session('staff.type') === 'admin';

    $feedbackCollection = method_exists($feedbacks, 'getCollection') ? $feedbacks->getCollection() : collect($feedbacks ?? []);
    $pelangganList = collect($pelanggan ?? []);
    $produkList = collect($produk ?? []);
    $kategoriList = collect($kategoriOptions ?? ['Rasa Produk', 'Pelayanan', 'Harga', 'Tempat/Suasana']);
    $sentiments = collect($sentimentData ?? []);
    $journeys = collect($journeyData ?? []);
    $ratings = collect($ratingData ?? []);

    $totalSentiment = max(1, (int) $sentiments->sum(fn ($item) => (int) data_get($item, 'count', 0)));
    $maxJourneyCount = max(1, (int) $journeys->max(fn ($item) => (int) data_get($item, 'count', 0)));

    $statusMeta = [
        'baru'     => ['label' => 'Baru', 'icon' => 'sparkles', 'tone' => '#5B8EA8', 'bg' => 'rgba(91, 142, 168, .12)', 'border' => 'rgba(91, 142, 168, .25)'],
        'ditinjau' => ['label' => 'Ditinjau', 'icon' => 'eye', 'tone' => '#C7955B', 'bg' => 'rgba(199, 149, 91, .14)', 'border' => 'rgba(199, 149, 91, .28)'],
        'selesai'  => ['label' => 'Selesai', 'icon' => 'check-circle-2', 'tone' => '#3F7D58', 'bg' => 'rgba(63, 125, 88, .12)', 'border' => 'rgba(63, 125, 88, .25)'],
    ];

    $sentimentByRating = function ($rating) {
        $rating = (int) $rating;
        if ($rating >= 4) return ['label' => 'Positif', 'icon' => 'smile-plus', 'tone' => '#3F7D58', 'bg' => 'rgba(63, 125, 88, .12)', 'border' => 'rgba(63, 125, 88, .25)'];
        if ($rating === 3) return ['label' => 'Netral', 'icon' => 'meh', 'tone' => '#C7955B', 'bg' => 'rgba(199, 149, 91, .14)', 'border' => 'rgba(199, 149, 91, .28)'];
        return ['label' => 'Pain Point', 'icon' => 'alert-triangle', 'tone' => '#B4533C', 'bg' => 'rgba(180, 83, 60, .12)', 'border' => 'rgba(180, 83, 60, .25)'];
    };

    $statCards = [
        ['label' => 'Total Feedback', 'value' => number_format((int) data_get($stats ?? [], 'total_feedback', 0), 0, ',', '.'), 'desc' => 'Seluruh suara pelanggan yang tercatat', 'icon' => 'message-square-text', 'tone' => '#6F4E37', 'bg' => 'rgba(111, 78, 55, .12)', 'border' => 'rgba(111, 78, 55, .24)'],
        ['label' => 'Feedback Bulan Ini', 'value' => number_format((int) data_get($stats ?? [], 'monthly_feedback', 0), 0, ',', '.'), 'desc' => 'Feedback yang masuk pada periode berjalan', 'icon' => 'calendar-days', 'tone' => '#5B8EA8', 'bg' => 'rgba(91, 142, 168, .12)', 'border' => 'rgba(91, 142, 168, .25)'],
        ['label' => 'Rata-rata Rating', 'value' => number_format((float) data_get($stats ?? [], 'avg_rating', 0), 1, ',', '.'), 'desc' => 'Nilai rata-rata pengalaman pelanggan', 'icon' => 'star', 'tone' => '#D4A853', 'bg' => 'rgba(212, 168, 83, .15)', 'border' => 'rgba(212, 168, 83, .28)'],
        ['label' => 'Pain Point', 'value' => number_format((int) data_get($stats ?? [], 'pain_points', 0), 0, ',', '.'), 'desc' => 'Feedback dengan rating rendah', 'icon' => 'alert-triangle', 'tone' => '#B4533C', 'bg' => 'rgba(180, 83, 60, .12)', 'border' => 'rgba(180, 83, 60, .25)'],
    ];
@endphp

<div class="feedback-page space-y-8">
    <section class="feedback-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.95fr] xl:items-end">
            <div>
                <span class="feedback-badge"><i data-lucide="message-circle-heart" class="h-4 w-4"></i> Voice of Customer</span>
                <h1 class="feedback-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">Feedback Pengalaman Pelanggan</h1>
                <p class="feedback-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Halaman ini digunakan untuk membaca komentar, rating, kategori pengalaman, status tindak lanjut, dan tahap customer journey pelanggan Aroma Coffee.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('feedback.create') }}" class="inline-flex items-center gap-2 rounded-full bg-[#3B271D] px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                        <i data-lucide="plus" class="h-4 w-4"></i> Catat Feedback
                    </a>
                    <a href="{{ route('cjm.index') }}" class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="map" class="h-4 w-4"></i> Journey Map
                    </a>
                    @if($isAdmin)
                        <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                            <i data-lucide="file-text" class="h-4 w-4"></i> Laporan
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($statCards as $card)
                    <article class="feedback-stat-card" style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                        <div class="relative z-10">
                            <div class="mb-3 feedback-icon-box" style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                                <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">{{ $card['label'] }}</p>
                            <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">{{ $card['value'] }}</p>
                            <p class="feedback-muted mt-2 text-xs leading-5">{{ $card['desc'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="feedback-panel p-5 md:p-7">
        <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="feedback-badge"><i data-lucide="filter" class="h-4 w-4"></i> Filter Data</span>
                <h2 class="feedback-title mt-4 font-serif text-3xl font-bold">Cari Feedback</h2>
                <p class="feedback-muted mt-2 text-sm leading-7">Filter berdasarkan pelanggan, produk, kategori, tahap journey, status, atau rating.</p>
            </div>
            @if(request()->hasAny(['q', 'kategori', 'tahap', 'status', 'rating']))
                <a href="{{ route('feedback.index') }}" class="inline-flex w-fit items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                    <i data-lucide="x" class="h-4 w-4"></i> Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('feedback.index') }}" class="grid gap-4 lg:grid-cols-[1.4fr_.8fr_.8fr_.8fr_.7fr_auto] lg:items-end">
            <div>
                <label for="q" class="mb-2 block text-sm">Pencarian</label>
                <input type="search" id="q" name="q" value="{{ request('q') }}" placeholder="Cari pelanggan, produk, atau komentar...">
            </div>
            <div>
                <label for="kategori" class="mb-2 block text-sm">Kategori</label>
                <select id="kategori" name="kategori">
                    <option value="">Semua</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tahap" class="mb-2 block text-sm">Tahap</label>
                <select id="tahap" name="tahap">
                    <option value="">Semua</option>
                    @foreach(\App\Models\Feedback::TAHAPAN as $key => $label)
                        <option value="{{ $key }}" @selected(request('tahap') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="mb-2 block text-sm">Status</label>
                <select id="status" name="status">
                    <option value="">Semua</option>
                    @foreach($statusMeta as $key => $meta)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="rating" class="mb-2 block text-sm">Rating</label>
                <select id="rating" name="rating">
                    <option value="">Semua</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((string) request('rating') === (string) $i)>{{ $i }} Bintang</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn h-[2.95rem]">
                <i data-lucide="search" class="h-4 w-4"></i> Cari
            </button>
        </form>
    </section>

    <!--<section class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
        <div class="feedback-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="feedback-badge"><i data-lucide="pie-chart" class="h-4 w-4"></i> Sentiment Overview</span>
                <h2 class="feedback-title mt-4 font-serif text-3xl font-bold">Distribusi Sentimen</h2>
                <p class="feedback-muted mt-2 text-sm leading-7">Sentimen dihitung dari rating: 4–5 positif, 3 netral, 1–2 pain point.</p>
            </div>
            <div class="feedback-chart-wrap">
                <canvas id="sentimentChart"></canvas>
            </div>
            <div class="mt-5 space-y-3">
                @foreach($sentiments as $item)
                    @php
                        $count = (int) data_get($item, 'count', 0);
                        $color = data_get($item, 'color', '#6F4E37');
                        $percent = ($count / $totalSentiment) * 100;
                    @endphp
                    <div class="feedback-list-card">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full" style="background: {{ $color }}"></span>
                                <p class="font-extrabold text-[#3B271D]">{{ data_get($item, 'name', '-') }}</p>
                            </div>
                            <p class="font-extrabold text-[#3B271D]">{{ number_format($count, 0, ',', '.') }}</p>
                        </div>
                        <div class="feedback-progress-track">
                            <div class="feedback-progress-fill" style="--tone: {{ $color }}; width: {{ min(100, max(0, $percent)) }}%;"></div>
                        </div>
                        <p class="feedback-muted mt-2 text-xs">
                            {{ rtrim(rtrim(number_format($percent, 1, ',', '.'), '0'), ',') }}% dari total feedback
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="feedback-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="feedback-badge"><i data-lucide="route" class="h-4 w-4"></i> Journey Distribution</span>
                <h2 class="feedback-title mt-4 font-serif text-3xl font-bold">Feedback per Tahap Journey</h2>
                <p class="feedback-muted mt-2 text-sm leading-7">Melihat tahap mana yang paling banyak menghasilkan suara pelanggan.</p>
            </div>
            <div class="grid gap-3 md:grid-cols-2">
                @foreach($journeys as $stage)
                    @php
                        $count = (int) data_get($stage, 'count', 0);
                        $avg = (float) data_get($stage, 'avg_rating', 0);
                        $width = ($count / $maxJourneyCount) * 100;
                        $meta = $sentimentByRating(round($avg ?: 3));
                    @endphp
                    <article class="feedback-stage-card" style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                                    Stage {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </p>
                                <h3 class="mt-1 font-extrabold text-[#3B271D]">{{ data_get($stage, 'label', '-') }}</h3>
                            </div>
                            <span class="feedback-pill" style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                {{ number_format($avg, 1, ',', '.') }}
                            </span>
                        </div>
                        <div class="feedback-progress-track">
                            <div class="feedback-progress-fill" style="--tone: {{ $meta['tone'] }}; width: {{ min(100, max(0, $width)) }}%;"></div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="feedback-muted">Jumlah feedback</span>
                            <span class="font-extrabold text-[#3B271D]">{{ $count }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>-->

    <section class="feedback-panel overflow-hidden">
        <div class="border-b border-[#6F4E37]/10 p-5 md:p-7">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="feedback-badge"><i data-lucide="database" class="h-4 w-4"></i> Feedback Records</span>
                    <h2 class="feedback-title mt-4 font-serif text-3xl font-bold">Data Feedback</h2>
                    <p class="feedback-muted mt-2 text-sm leading-7">Daftar komentar pelanggan beserta kategori, rating, tahap journey, dan status tindak lanjut.</p>
                </div>
                <span class="feedback-pill w-fit" style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                    {{ number_format($feedbackCollection->count(), 0, ',', '.') }} data tampil
                </span>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="feedback-table-wrap">
                <table class="feedback-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Tahap</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            @if($isAdmin) <th class="text-right">Aksi</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $f)
                            @php
                                $sentiment = $sentimentByRating($f->rating);
                                $status = $statusMeta[$f->status] ?? $statusMeta['baru'];
                            @endphp
                            <tr>
                                <td>
                                    <div class="font-bold text-[#3B271D]">{{ optional($f->tanggal_feedback)->format('d M Y') }}</div>
                                    <div class="feedback-muted mt-1 text-xs">{{ optional($f->tanggal_feedback)->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <div class="font-bold text-[#3B271D]">{{ optional($f->pelanggan)->nama ?? '-' }}</div>
                                    <div class="feedback-muted mt-1 text-xs">{{ optional($f->pelanggan)->nomor_hp ?? 'Nomor tidak tersedia' }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-[#3B271D]">{{ optional($f->produk)->nama_produk ?? '-' }}</div>
                                    <div class="feedback-muted mt-1 text-xs">{{ optional($f->produk)->kategori ?? 'Menu' }}</div>
                                </td>
                                <td>
                                    <span class="feedback-pill" style="--tone: #A7784D; --tone-bg: rgba(199, 149, 91, .12); --tone-border: rgba(199, 149, 91, .24);">
                                        {{ $f->kategori }}
                                    </span>
                                </td>
                                <td>
                                    <span class="feedback-pill" style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                                        {{ $f->tahap_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="feedback-rating"><i data-lucide="star" class="h-4 w-4 fill-current"></i> {{ $f->rating }}/5</div>
                                    <div class="mt-2">
                                        <span class="feedback-pill" style="--tone: {{ $sentiment['tone'] }}; --tone-bg: {{ $sentiment['bg'] }}; --tone-border: {{ $sentiment['border'] }};">
                                            <i data-lucide="{{ $sentiment['icon'] }}" class="h-3.5 w-3.5"></i> {{ $sentiment['label'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="max-w-xs">
                                    <p class="line-clamp-3 text-sm italic leading-6 text-[#3B271D]/68">
                                        “{{ \Illuminate\Support\Str::limit($f->komentar, 130) }}”
                                    </p>
                                </td>
                                <td>
                                    <span class="feedback-pill" style="--tone: {{ $status['tone'] }}; --tone-bg: {{ $status['bg'] }}; --tone-border: {{ $status['border'] }};">
                                        <i data-lucide="{{ $status['icon'] }}" class="h-3.5 w-3.5"></i> {{ $status['label'] }}
                                    </span>
                                </td>
                                @if($isAdmin)
                                    <td>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" class="feedback-action" onclick="document.getElementById('editFeedback{{ $f->id_feedback }}').showModal()" title="Edit feedback">
                                                <i data-lucide="pencil" class="h-4 w-4"></i>
                                            </button>
                                            <form method="POST" action="{{ route('feedback.destroy', $f) }}" onsubmit="return confirm('Hapus feedback ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="feedback-action feedback-danger" title="Hapus feedback"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 9 : 8 }}">
                                    <div class="py-10 text-center">
                                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]"><i data-lucide="message-circle" class="h-7 w-7"></i></div>
                                        <p class="font-bold text-[#3B271D]">Belum ada feedback</p>
                                        <p class="feedback-muted mt-2 text-sm">Data akan muncul setelah feedback pelanggan dicatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-4 p-5 md:hidden">
            @forelse($feedbacks as $f)
                @php
                    $sentiment = $sentimentByRating($f->rating);
                    $status = $statusMeta[$f->status] ?? $statusMeta['baru'];
                @endphp
                <article class="feedback-mobile-card">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate font-extrabold text-[#3B271D]">{{ optional($f->pelanggan)->nama ?? '-' }}</p>
                            <p class="feedback-muted mt-1 text-xs">{{ optional($f->tanggal_feedback)->format('d M Y H:i') }} WIB</p>
                        </div>
                        <span class="feedback-rating shrink-0"><i data-lucide="star" class="h-4 w-4 fill-current"></i> {{ $f->rating }}/5</span>
                    </div>
                    <p class="text-sm italic leading-6 text-[#3B271D]/70">“{{ \Illuminate\Support\Str::limit($f->komentar, 150) }}”</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="feedback-pill" style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">{{ $f->tahap_label }}</span>
                        <span class="feedback-pill" style="--tone: {{ $sentiment['tone'] }}; --tone-bg: {{ $sentiment['bg'] }}; --tone-border: {{ $sentiment['border'] }};">{{ $sentiment['label'] }}</span>
                        <span class="feedback-pill" style="--tone: {{ $status['tone'] }}; --tone-bg: {{ $status['bg'] }}; --tone-border: {{ $status['border'] }};">{{ $status['label'] }}</span>
                    </div>
                    <div class="mt-4 rounded-2xl bg-[#F8EFE3]/60 p-3">
                        <p class="text-xs font-bold uppercase tracking-[.12em] text-[#A7784D]">Produk</p>
                        <p class="mt-1 font-bold text-[#3B271D]">{{ optional($f->produk)->nama_produk ?? '-' }}</p>
                    </div>
                    @if($isAdmin)
                        <div class="mt-4 flex gap-2">
                            <button type="button" class="btn-secondary flex-1" onclick="document.getElementById('editFeedback{{ $f->id_feedback }}').showModal()"><i data-lucide="pencil" class="h-4 w-4"></i> Edit</button>
                            <form method="POST" action="{{ route('feedback.destroy', $f) }}" class="flex-1" onsubmit="return confirm('Hapus feedback ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex h-[2.65rem] w-full items-center justify-center gap-2 rounded-full bg-red-700 px-4 text-sm font-bold text-white"><i data-lucide="trash-2" class="h-4 w-4"></i> Hapus</button>
                            </form>
                        </div>
                    @endif
                </article>
            @empty
                <div class="feedback-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]"><i data-lucide="message-circle" class="h-7 w-7"></i></div>
                        <p class="font-bold text-[#3B271D]">Belum ada feedback</p>
                        <p class="feedback-muted mt-2 text-sm leading-6">Data akan muncul setelah feedback pelanggan dicatat.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(method_exists($feedbacks, 'links'))
            <div class="border-t border-[#6F4E37]/10 p-5">{{ $feedbacks->links() }}</div>
        @endif
    </section>

    @if($isAdmin)
        @foreach($feedbackCollection as $f)
            <dialog id="editFeedback{{ $f->id_feedback }}" class="feedback-dialog">
                <div class="feedback-dialog-card">
                    <div class="flex items-start justify-between gap-4 border-b border-[#6F4E37]/10 p-5 md:p-6">
                        <div>
                            <span class="feedback-badge"><i data-lucide="pencil" class="h-4 w-4"></i> Edit Feedback</span>
                            <h3 class="feedback-title mt-3 font-serif text-3xl font-bold">Perbarui Data Feedback</h3>
                            <p class="feedback-muted mt-2 text-sm leading-6">Ubah informasi feedback, status tindak lanjut, atau tahap journey.</p>
                        </div>
                        <button type="button" class="rounded-2xl bg-[#F8EFE3] p-3 text-[#6F4E37]" onclick="document.getElementById('editFeedback{{ $f->id_feedback }}').close()"><i data-lucide="x" class="h-5 w-5"></i></button>
                    </div>
                    <form method="POST" action="{{ route('feedback.update', $f) }}" class="p-5 md:p-6">
                        @csrf @method('PUT')
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm">Pelanggan</label>
                                <select name="id_pelanggan" required>
                                    @foreach($pelangganList as $c)
                                        <option value="{{ $c->id_pelanggan }}" @selected($f->id_pelanggan == $c->id_pelanggan)>{{ $c->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm">Produk</label>
                                <select name="id_produk" required>
                                    @foreach($produkList as $p)
                                        <option value="{{ $p->id_produk }}" @selected($f->id_produk == $p->id_produk)>{{ $p->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm">Kategori</label>
                                <select name="kategori" required>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori }}" @selected($f->kategori === $kategori)>{{ $kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm">Tahap Journey</label>
                                <select name="tahap_journey" required>
                                    @foreach(\App\Models\Feedback::TAHAPAN as $key => $label)
                                        <option value="{{ $key }}" @selected($f->tahap_journey === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm">Rating</label>
                                <select name="rating" required>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected((int) $f->rating === $i)>{{ $i }} Bintang</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm">Status</label>
                                <select name="status" required>
                                    @foreach($statusMeta as $key => $meta)
                                        <option value="{{ $key }}" @selected($f->status === $key)>{{ $meta['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm">Komentar</label>
                                <textarea name="komentar" rows="4" required>{{ $f->komentar }}</textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('editFeedback{{ $f->id_feedback }}').close()">Batal</button>
                            <button type="submit" class="btn"><i data-lucide="save" class="h-4 w-4"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </dialog>
        @endforeach
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) { lucide.createIcons(); }
        if (typeof Chart === 'undefined') { return; }

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = 'rgba(59, 39, 29, .62)';

        const sentimentData = @json($sentiments->values());
        const ratingData = @json($ratings->values());
        const sentimentCanvas = document.getElementById('sentimentChart');

        if (sentimentCanvas && sentimentData.length) {
            new Chart(sentimentCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: sentimentData.map(item => item.name),
                    datasets: [{
                        data: sentimentData.map(item => Number(item.count || 0)),
                        backgroundColor: sentimentData.map(item => item.color || '#6F4E37'),
                        borderColor: '#FDF9F3',
                        borderWidth: 5,
                        hoverOffset: 7
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '68%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, boxHeight: 8, font: { weight: '700' } } },
                        tooltip: { backgroundColor: '#241711', padding: 14, displayColors: false, cornerRadius: 14 }
                    }
                }
            });
        }
    });
</script>
@endpush
