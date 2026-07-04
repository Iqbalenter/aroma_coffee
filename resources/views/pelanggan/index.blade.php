@extends('layouts.app')

@section('title', 'Pelanggan')

@push('head')
<style>
    .customer-page { color: #241711; }

    .customer-hero {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background:
            radial-gradient(circle at 10% 8%, rgba(199, 149, 91, .24), transparent 28%),
            radial-gradient(circle at 90% 0%, rgba(111, 78, 55, .14), transparent 26%),
            linear-gradient(135deg, rgba(253, 249, 243, .98), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }

    .customer-panel {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    .customer-badge {
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

    .customer-title { color: #3B271D; letter-spacing: -.02em; }
    .customer-muted { color: rgba(36, 23, 17, .58); }

    .customer-stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.35rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.15rem;
    }

    .customer-stat-card::after {
        content: "";
        position: absolute;
        right: -2.25rem;
        top: -2.25rem;
        width: 6.5rem;
        height: 6.5rem;
        border-radius: 999px;
        background: var(--tone-bg);
    }

    .customer-icon-box {
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

    .customer-pill {
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

    .customer-chart-wrap { position: relative; min-height: 300px; }

    .customer-status-card,
    .customer-list-card {
        border-radius: 1.25rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .09);
        padding: 1rem;
    }

    .customer-progress-track {
        height: .62rem;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(232, 214, 191, .62);
    }

    .customer-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--tone);
    }

    .customer-table-wrap { overflow-x: auto; }

    .customer-table {
        min-width: 1050px;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .customer-table thead th {
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

    .customer-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid rgba(111, 78, 55, .08);
        color: rgba(36, 23, 17, .82);
        font-size: .875rem;
        vertical-align: top;
    }

    .customer-table tbody tr:hover {
        background: rgba(248, 239, 227, .42);
    }

    .customer-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.35rem;
        height: 2.35rem;
        border-radius: .9rem;
        border: 1px solid rgba(111, 78, 55, .10);
        background: rgba(255, 255, 255, .72);
        color: #6F4E37;
        transition: all .18s ease;
    }

    .customer-action:hover { background: linear-gradient(135deg, var(--caramel), var(--mocha)); color: white; }
    .customer-danger:hover { background: #B4533C; color: white; }

    .customer-mobile-card {
        border-radius: 1.35rem;
        background: rgba(255, 255, 255, .82);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07);
        padding: 1rem;
    }

    .customer-empty {
        display: flex;
        min-height: 240px;
        align-items: center;
        justify-content: center;
        border-radius: 1.5rem;
        background: rgba(248, 239, 227, .42);
        border: 1px dashed rgba(111, 78, 55, .22);
        text-align: center;
    }

    .customer-dialog {
        width: min(740px, calc(100% - 2rem));
        border: 0;
        border-radius: 1.7rem;
        padding: 0;
        background: transparent;
    }

    .customer-dialog::backdrop {
        background: rgba(36, 23, 17, .55);
        backdrop-filter: blur(6px);
    }

    .customer-dialog-card {
        background: #FDF9F3;
        border: 1px solid rgba(111, 78, 55, .12);
        border-radius: 1.7rem;
        box-shadow: 0 24px 60px rgba(36, 23, 17, .28);
        overflow: hidden;
    }

    .customer-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .customer-hero, .customer-panel { border-radius: 1.5rem; }
        .customer-chart-wrap { min-height: 250px; }
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = session('staff.type') === 'admin';

    $pelangganCollection = method_exists($pelanggan, 'getCollection')
        ? $pelanggan->getCollection()
        : collect($pelanggan ?? []);

    $statusList = collect($statusData ?? []);
    $statusMap = collect($statusMeta ?? []);

    $totalCustomers = (int) data_get($stats ?? [], 'total_customers', 0);
    $activeCustomers = (int) data_get($stats ?? [], 'active_customers', 0);
    $loyalCustomers = (int) data_get($stats ?? [], 'loyal_customers', 0);
    $inactiveCustomers = (int) data_get($stats ?? [], 'inactive_customers', 0);
    $newThisMonth = (int) data_get($stats ?? [], 'new_this_month', 0);

    $activePercent = $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100, 1) : 0;
    $loyalPercent = $totalCustomers > 0 ? round(($loyalCustomers / $totalCustomers) * 100, 1) : 0;
    $inactivePercent = $totalCustomers > 0 ? round(($inactiveCustomers / $totalCustomers) * 100, 1) : 0;

    $formatNumber = fn ($number) => number_format((float) $number, 0, ',', '.');
    $formatMoney = fn ($number) => 'Rp ' . number_format((float) $number, 0, ',', '.');

    $statusFor = function ($key) use ($statusMap) {
        return $statusMap->get($key, [
            'label' => ucfirst((string) $key),
            'icon' => 'users',
            'tone' => '#6F4E37',
            'bg' => 'rgba(111, 78, 55, .12)',
            'border' => 'rgba(111, 78, 55, .24)',
            'desc' => 'Status pelanggan.',
        ]);
    };

    $statCards = [
        [
            'label' => 'Total Pelanggan',
            'value' => $formatNumber($totalCustomers),
            'desc' => 'Seluruh profil pelanggan yang tercatat',
            'icon' => 'users',
            'tone' => '#6F4E37',
            'bg' => 'rgba(111, 78, 55, .12)',
            'border' => 'rgba(111, 78, 55, .24)',
        ],
        [
            'label' => 'Pelanggan Baru',
            'value' => $formatNumber($newThisMonth),
            'desc' => 'Pelanggan yang terdaftar bulan ini',
            'icon' => 'user-plus',
            'tone' => '#5B8EA8',
            'bg' => 'rgba(91, 142, 168, .12)',
            'border' => 'rgba(91, 142, 168, .25)',
        ],
        [
            'label' => 'Pelanggan Aktif',
            'value' => rtrim(rtrim(number_format($activePercent, 1, ',', '.'), '0'), ',') . '%',
            'desc' => 'Aktif, potensial loyal, dan loyal',
            'icon' => 'activity',
            'tone' => '#3F7D58',
            'bg' => 'rgba(63, 125, 88, .12)',
            'border' => 'rgba(63, 125, 88, .25)',
        ],
        [
            'label' => 'Pelanggan Loyal',
            'value' => rtrim(rtrim(number_format($loyalPercent, 1, ',', '.'), '0'), ',') . '%',
            'desc' => 'Basis pelanggan dengan loyalitas terbaik',
            'icon' => 'heart-handshake',
            'tone' => '#D4A853',
            'bg' => 'rgba(212, 168, 83, .15)',
            'border' => 'rgba(212, 168, 83, .30)',
        ],
    ];
@endphp

<div class="customer-page space-y-8">

    {{-- Hero Section --}}
    <section class="customer-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.95fr] xl:items-end">
            <div>
                <span class="customer-badge">
                    <i data-lucide="users-round" class="h-4 w-4"></i>
                    Customer Database
                </span>

                <h1 class="customer-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">
                    Data Pelanggan Aroma Coffee
                </h1>

                <p class="customer-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Halaman ini digunakan untuk mengelola profil pelanggan, membaca status segmentasi,
                    riwayat transaksi, jumlah feedback, dan potensi loyalitas pelanggan.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="button"
                            onclick="document.getElementById('createCustomerDialog').showModal()"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-coffee-caramel to-coffee-mocha px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Pelanggan Baru
                    </button>

                    <a href="{{ route('transaksi.create') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="receipt" class="h-4 w-4"></i>
                        Input Transaksi
                    </a>

                    @if($isAdmin)
                        <a href="{{ route('segmentasi.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                            <i data-lucide="pie-chart" class="h-4 w-4"></i>
                            Segmentasi
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($statCards as $card)
                    <article class="customer-stat-card"
                             style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                        <div class="relative z-10">
                            <div class="mb-3 customer-icon-box"
                                 style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                                <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                            </div>

                            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                                {{ $card['label'] }}
                            </p>

                            <p class="mt-2 text-3xl font-extrabold text-[#3B271D]">
                                {{ $card['value'] }}
                            </p>

                            <p class="customer-muted mt-2 text-xs leading-5">
                                {{ $card['desc'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Filter Section --}}
    <section class="customer-panel p-5 md:p-7">
        <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="customer-badge">
                    <i data-lucide="filter" class="h-4 w-4"></i>
                    Filter Pelanggan
                </span>

                <h2 class="customer-title mt-4 font-serif text-3xl font-bold">
                    Cari dan Kelompokkan Data
                </h2>

                <p class="customer-muted mt-2 text-sm leading-7">
                    Gunakan pencarian nama, email, nomor HP, alamat, atau filter status segmentasi.
                </p>
            </div>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('pelanggan.index') }}"
                   class="inline-flex w-fit items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                    <i data-lucide="x" class="h-4 w-4"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('pelanggan.index') }}" class="grid gap-4 lg:grid-cols-[1.5fr_.8fr_auto] lg:items-end">
            <div>
                <label for="search" class="mb-2 block text-sm">Pencarian</label>
                <input type="search"
                       id="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama, email, nomor HP, atau alamat...">
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm">Status</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    @foreach($statusMap as $key => $meta)
                        <option value="{{ $key }}" @selected($status === $key)>
                            {{ $meta['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn h-[2.95rem]">
                <i data-lucide="search" class="h-4 w-4"></i>
                Cari
            </button>
        </form>
    </section>

    <!--{{-- Stats & Chart Section --}}
    <section class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
        <div class="customer-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="customer-badge">
                    <i data-lucide="pie-chart" class="h-4 w-4"></i>
                    Customer Segment
                </span>

                <h2 class="customer-title mt-4 font-serif text-3xl font-bold">
                    Distribusi Status Pelanggan
                </h2>

                <p class="customer-muted mt-2 text-sm leading-7">
                    Ringkasan komposisi pelanggan berdasarkan status segmentasi.
                </p>
            </div>

            <div class="customer-chart-wrap">
                <canvas id="customerStatusChart"></canvas>
            </div>

            <div class="mt-5 space-y-3">
                @foreach($statusList as $item)
                    <div class="customer-list-card"
                         style="--tone: {{ $item['tone'] }}; --tone-bg: {{ $item['bg'] }}; --tone-border: {{ $item['border'] }};">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="h-3 w-3 shrink-0 rounded-full" style="background: {{ $item['tone'] }}"></span>
                                <p class="truncate font-extrabold text-[#3B271D]">{{ $item['label'] }}</p>
                            </div>

                            <p class="font-extrabold text-[#3B271D]">
                                {{ $formatNumber($item['count']) }}
                            </p>
                        </div>

                        <div class="customer-progress-track">
                            <div class="customer-progress-fill"
                                 style="--tone: {{ $item['tone'] }}; width: {{ min(100, max(0, $item['percent'])) }}%;"></div>
                        </div>

                        <p class="customer-muted mt-2 text-xs">
                            {{ rtrim(rtrim(number_format($item['percent'], 1, ',', '.'), '0'), ',') }}% dari total pelanggan
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="customer-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="customer-badge">
                    <i data-lucide="route" class="h-4 w-4"></i>
                    Loyalty Reading
                </span>

                <h2 class="customer-title mt-4 font-serif text-3xl font-bold">
                    Pembacaan Status Pelanggan
                </h2>

                <p class="customer-muted mt-2 text-sm leading-7">
                    Status membantu menentukan perlakuan layanan, promosi, dan strategi retensi.
                </p>
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                @foreach($statusList as $item)
                    <article class="customer-status-card"
                             style="--tone: {{ $item['tone'] }}; --tone-bg: {{ $item['bg'] }}; --tone-border: {{ $item['border'] }};">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div class="customer-icon-box"
                                 style="--tone: {{ $item['tone'] }}; --tone-bg: {{ $item['bg'] }}; --tone-border: {{ $item['border'] }};">
                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                            </div>

                            <span class="customer-pill"
                                  style="--tone: {{ $item['tone'] }}; --tone-bg: {{ $item['bg'] }}; --tone-border: {{ $item['border'] }};">
                                {{ $item['count'] }} orang
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-[#3B271D]">
                            {{ $item['label'] }}
                        </h3>

                        <p class="customer-muted mt-2 min-h-[3rem] text-sm leading-6">
                            {{ $item['desc'] }}
                        </p>
                    </article>
                @endforeach
            </div>

            <div class="mt-5 rounded-3xl border border-[#6F4E37]/10 bg-[#F8EFE3]/55 p-5">
                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-coffee-caramel to-coffee-mocha text-white">
                        <i data-lucide="lightbulb" class="h-5 w-5"></i>
                    </div>

                    <div>
                        <p class="font-extrabold text-[#3B271D]">Catatan Analisis</p>
                        <p class="customer-muted mt-1 text-sm leading-6">
                            Persentase pelanggan tidak aktif saat ini
                            <strong>{{ rtrim(rtrim(number_format($inactivePercent, 1, ',', '.'), '0'), ',') }}%</strong>.
                            Angka ini bisa digunakan sebagai dasar rekomendasi reaktivasi pelanggan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    {{-- Main Table Section --}}
    <section class="customer-panel overflow-hidden">
        <div class="border-b border-[#6F4E37]/10 p-5 md:p-7">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="customer-badge">
                        <i data-lucide="database" class="h-4 w-4"></i>
                        Customer Records
                    </span>

                    <h2 class="customer-title mt-4 font-serif text-3xl font-bold">
                        Daftar Pelanggan
                    </h2>

                    <p class="customer-muted mt-2 text-sm leading-7">
                        Data pelanggan beserta kontak, tanggal daftar, status, transaksi, feedback, dan rating.
                    </p>
                </div>

                <span class="customer-pill w-fit"
                      style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                    {{ method_exists($pelanggan, 'total') ? $formatNumber($pelanggan->total()) : $formatNumber($pelangganCollection->count()) }} data
                </span>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block">
            <div class="customer-table-wrap">
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Transaksi</th>
                            <th>Feedback</th>
                            <th>Rating</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pelanggan as $p)
                            @php
                                $meta = $statusFor($p->status);
                                $rataRating = (float) ($p->rata_rating ?? 0);
                            @endphp

                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-coffee-caramel to-coffee-mocha text-sm font-extrabold text-white">
                                            {{ strtoupper(\Illuminate\Support\Str::substr($p->nama, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate font-extrabold text-[#3B271D]">
                                                {{ $p->nama }}
                                            </p>

                                            <p class="customer-muted mt-1 text-xs">
                                                ID: {{ $p->id_pelanggan }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="font-bold text-[#3B271D]">
                                        {{ $p->nomor_hp ?: 'Nomor tidak tersedia' }}
                                    </div>

                                    <div class="customer-muted mt-1 text-xs">
                                        {{ $p->email ?: 'Email tidak tersedia' }}
                                    </div>
                                </td>

                                <td class="max-w-xs">
                                    <p class="customer-clamp text-sm leading-6 text-[#3B271D]/65">
                                        {{ $p->alamat ?: 'Alamat belum diisi.' }}
                                    </p>
                                </td>

                                <td>
                                    <div class="font-bold text-[#3B271D]">
                                        {{ optional($p->tanggal_daftar)->format('d M Y') }}
                                    </div>

                                    <div class="customer-muted mt-1 text-xs">
                                        {{ optional($p->tanggal_daftar)->format('H:i') }} WIB
                                    </div>
                                </td>

                                <td>
                                    <span class="customer-pill"
                                          style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                        <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5"></i>
                                        {{ $meta['label'] }}
                                    </span>
                                </td>

                                <td>
                                    <div class="font-extrabold text-[#3B271D]">
                                        {{ $formatNumber($p->transaksi_count ?? 0) }}
                                    </div>

                                    <div class="customer-muted mt-1 text-xs">
                                        {{ $formatMoney($p->total_belanja ?? 0) }}
                                    </div>
                                </td>

                                <td>
                                    <div class="font-extrabold text-[#3B271D]">
                                        {{ $formatNumber($p->feedback_count ?? 0) }}
                                    </div>

                                    <div class="customer-muted mt-1 text-xs">
                                        suara pelanggan
                                    </div>
                                </td>

                                <td>
                                    <div class="inline-flex items-center gap-1 font-extrabold text-[#C7955B]">
                                        <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                        {{ $rataRating > 0 ? number_format($rataRating, 1, ',', '.') : '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                class="customer-action"
                                                onclick="document.getElementById('editCustomer{{ $p->id_pelanggan }}').showModal()"
                                                title="Edit pelanggan">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </button>

                                        {{-- Cek role user --}}
                                        @if($isAdmin)
                                            <form method="POST"
                                                  action="{{ route('pelanggan.destroy', $p) }}"
                                                  onsubmit="return confirm('Hapus pelanggan ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="customer-action customer-danger"
                                                        title="Hapus pelanggan">
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="py-10 text-center">
                                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                            <i data-lucide="users" class="h-7 w-7"></i>
                                        </div>

                                        <p class="font-bold text-[#3B271D]">Data pelanggan tidak ditemukan</p>
                                        <p class="customer-muted mt-2 text-sm">
                                            Tambahkan pelanggan baru atau ubah filter pencarian.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile View --}}
        <div class="grid gap-4 p-5 md:hidden">
            @forelse($pelanggan as $p)
                @php
                    $meta = $statusFor($p->status);
                    $rataRating = (float) ($p->rata_rating ?? 0);
                @endphp

                <article class="customer-mobile-card">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-coffee-caramel to-coffee-mocha text-sm font-extrabold text-white">
                                {{ strtoupper(\Illuminate\Support\Str::substr($p->nama, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate font-extrabold text-[#3B271D]">{{ $p->nama }}</p>
                                <p class="customer-muted mt-1 text-xs">ID: {{ $p->id_pelanggan }}</p>
                            </div>
                        </div>

                        <span class="customer-pill shrink-0"
                              style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                            {{ $meta['label'] }}
                        </span>
                    </div>

                    <div class="space-y-3 rounded-2xl bg-[#F8EFE3]/55 p-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.12em] text-[#A7784D]">Kontak</p>
                            <p class="mt-1 font-bold text-[#3B271D]">{{ $p->nomor_hp ?: 'Nomor tidak tersedia' }}</p>
                            <p class="customer-muted mt-1 text-sm">{{ $p->email ?: 'Email tidak tersedia' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-[.12em] text-[#A7784D]">Alamat</p>
                            <p class="customer-muted mt-1 text-sm leading-6">{{ $p->alamat ?: 'Alamat belum diisi.' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <div class="rounded-2xl bg-white/70 p-3">
                            <p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-[#A7784D]">Trx</p>
                            <p class="mt-1 text-lg font-extrabold text-[#3B271D]">{{ $p->transaksi_count ?? 0 }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/70 p-3">
                            <p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-[#A7784D]">Feedback</p>
                            <p class="mt-1 text-lg font-extrabold text-[#3B271D]">{{ $p->feedback_count ?? 0 }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/70 p-3">
                            <p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-[#A7784D]">Rating</p>
                            <p class="mt-1 text-lg font-extrabold text-[#3B271D]">
                                {{ $rataRating > 0 ? number_format($rataRating, 1, ',', '.') : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button type="button"
                                class="btn-secondary flex-1"
                                onclick="document.getElementById('editCustomer{{ $p->id_pelanggan }}').showModal()">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                            Edit
                        </button>

                        @if($isAdmin)
                            <form method="POST"
                                  action="{{ route('pelanggan.destroy', $p) }}"
                                  class="flex-1"
                                  onsubmit="return confirm('Hapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex h-[2.65rem] w-full items-center justify-center gap-2 rounded-full bg-red-700 px-4 text-sm font-bold text-white">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="customer-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                            <i data-lucide="users" class="h-7 w-7"></i>
                        </div>

                        <p class="font-bold text-[#3B271D]">Data pelanggan tidak ditemukan</p>
                        <p class="customer-muted mt-2 text-sm leading-6">
                            Tambahkan pelanggan baru atau ubah filter pencarian.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(method_exists($pelanggan, 'links'))
            <div class="border-t border-[#6F4E37]/10 p-5">
                {{ $pelanggan->links() }}
            </div>
        @endif
    </section>

    {{-- Modal Create --}}
    <dialog id="createCustomerDialog" class="customer-dialog">
        <div class="customer-dialog-card">
            <div class="flex items-start justify-between gap-4 border-b border-[#6F4E37]/10 p-5 md:p-6">
                <div>
                    <span class="customer-badge">
                        <i data-lucide="user-plus" class="h-4 w-4"></i>
                        Pelanggan Baru
                    </span>

                    <h3 class="customer-title mt-3 font-serif text-3xl font-bold">
                        Tambah Data Pelanggan
                    </h3>

                    <p class="customer-muted mt-2 text-sm leading-6">
                        Lengkapi identitas pelanggan untuk kebutuhan transaksi dan feedback.
                    </p>
                </div>

                <button type="button"
                        class="rounded-2xl bg-[#F8EFE3] p-3 text-[#6F4E37]"
                        onclick="document.getElementById('createCustomerDialog').close()">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('pelanggan.store') }}" class="p-5 md:p-6">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="nama" class="mb-2 block text-sm">Nama Pelanggan</label>
                        <input id="nama"
                               type="text"
                               name="nama"
                               value="{{ old('nama') }}"
                               placeholder="Contoh: Winda Sari"
                               required>
                    </div>

                    <div>
                        <label for="nomor_hp" class="mb-2 block text-sm">Nomor HP</label>
                        <input id="nomor_hp"
                               type="text"
                               name="nomor_hp"
                               value="{{ old('nomor_hp') }}"
                               placeholder="Contoh: 081234567890">
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="mb-2 block text-sm">Email</label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Contoh: pelanggan@email.com">
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm">Alamat</label>
                        <textarea id="alamat"
                                  name="alamat"
                                  rows="4"
                                  placeholder="Masukkan alamat pelanggan">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button"
                            class="btn-secondary"
                            onclick="document.getElementById('createCustomerDialog').close()">
                        Batal
                    </button>

                    <button type="submit" class="btn">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Simpan Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Modal Edit (Looping) --}}
    @foreach($pelangganCollection as $p)
        <dialog id="editCustomer{{ $p->id_pelanggan }}" class="customer-dialog">
            <div class="customer-dialog-card">
                <div class="flex items-start justify-between gap-4 border-b border-[#6F4E37]/10 p-5 md:p-6">
                    <div>
                        <span class="customer-badge">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                            Edit Pelanggan
                        </span>

                        <h3 class="customer-title mt-3 font-serif text-3xl font-bold">
                            Perbarui Data Pelanggan
                        </h3>

                        <p class="customer-muted mt-2 text-sm leading-6">
                            Status segmentasi tetap dihitung otomatis dari aktivitas pelanggan.
                        </p>
                    </div>

                    <button type="button"
                            class="rounded-2xl bg-[#F8EFE3] p-3 text-[#6F4E37]"
                            onclick="document.getElementById('editCustomer{{ $p->id_pelanggan }}').close()">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('pelanggan.update', $p) }}" class="p-5 md:p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm">Nama Pelanggan</label>
                            <input type="text"
                                   name="nama"
                                   value="{{ old('nama', $p->nama) }}"
                                   required>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm">Nomor HP</label>
                            <input type="text"
                                   name="nomor_hp"
                                   value="{{ old('nomor_hp', $p->nomor_hp) }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm">Email</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $p->email) }}">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm">Alamat</label>
                            <textarea name="alamat" rows="4">{{ old('alamat', $p->alamat) }}</textarea>
                        </div>

                        <div class="md:col-span-2 rounded-2xl bg-[#F8EFE3]/65 p-4">
                            @php
                                $meta = $statusFor($p->status);
                            @endphp

                            <p class="mb-2 text-xs font-extrabold uppercase tracking-[.14em] text-[#A7784D]">
                                Status Saat Ini
                            </p>

                            <span class="customer-pill"
                                  style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5"></i>
                                {{ $meta['label'] }}
                            </span>

                            <p class="customer-muted mt-3 text-sm leading-6">
                                Status pelanggan diperbarui otomatis dari transaksi dan feedback.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button"
                                class="btn-secondary"
                                onclick="document.getElementById('editCustomer{{ $p->id_pelanggan }}').close()">
                            Batal
                        </button>

                        <button type="submit" class="btn">
                            <i data-lucide="save" class="h-4 w-4"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </dialog>
    @endforeach
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

        const statusData = @json($statusList->values());
        const chartCanvas = document.getElementById('customerStatusChart');

        if (chartCanvas && statusData.length) {
            new Chart(chartCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusData.map(item => item.label),
                    datasets: [{
                        data: statusData.map(item => Number(item.count || 0)),
                        backgroundColor: statusData.map(item => item.tone || '#6F4E37'),
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
                            position: 'bottom',
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
                            displayColors: false,
                            cornerRadius: 14,
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
        }
    });
</script>
@endpush
