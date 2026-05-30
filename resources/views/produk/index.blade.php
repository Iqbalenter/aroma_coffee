@extends('layouts.app')

@section('title', 'Produk')

@push('head')
<style>
    .product-page { color: #241711; }

    .product-hero {
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

    .product-panel {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    .product-badge {
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

    .product-title { color: #3B271D; letter-spacing: -.02em; }
    .product-muted { color: rgba(36, 23, 17, .58); }

    .product-stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.35rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .10);
        padding: 1.15rem;
    }

    .product-stat-card::after {
        content: "";
        position: absolute;
        right: -2.25rem;
        top: -2.25rem;
        width: 6.5rem;
        height: 6.5rem;
        border-radius: 999px;
        background: var(--tone-bg);
    }

    .product-icon-box {
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

    .product-pill {
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

    .product-chart-wrap { position: relative; min-height: 285px; }

    .product-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.55rem;
        background: rgba(255, 255, 255, .82);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 38px rgba(68, 42, 25, .12);
    }

    .product-image {
        position: relative;
        height: 12.5rem;
        overflow: hidden;
        background:
            radial-gradient(circle at 30% 20%, rgba(199, 149, 91, .24), transparent 34%),
            linear-gradient(135deg, #F8EFE3, #E8D6BF);
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-image-placeholder {
        display: flex;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #6F4E37;
    }

    .product-list-card {
        border-radius: 1.25rem;
        background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .09);
        padding: 1rem;
    }

    .product-progress-track {
        height: .62rem;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(232, 214, 191, .62);
    }

    .product-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--tone);
    }

    .product-action {
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

    .product-action:hover { background: #3B271D; color: white; }
    .product-danger:hover { background: #B4533C; color: white; }

    .product-empty {
        display: flex;
        min-height: 260px;
        align-items: center;
        justify-content: center;
        border-radius: 1.5rem;
        background: rgba(248, 239, 227, .42);
        border: 1px dashed rgba(111, 78, 55, .22);
        text-align: center;
    }

    .product-dialog {
        width: min(780px, calc(100% - 2rem));
        border: 0;
        border-radius: 1.7rem;
        padding: 0;
        background: transparent;
    }

    .product-dialog::backdrop {
        background: rgba(36, 23, 17, .55);
        backdrop-filter: blur(6px);
    }

    .product-dialog-card {
        background: #FDF9F3;
        border: 1px solid rgba(111, 78, 55, .12);
        border-radius: 1.7rem;
        box-shadow: 0 24px 60px rgba(36, 23, 17, .28);
        overflow: hidden;
    }

    .product-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .product-hero, .product-panel { border-radius: 1.5rem; }
        .product-image { height: 11rem; }
    }
</style>
@endpush

@section('content')
@php
    $produkCollection = method_exists($produk, 'getCollection')
        ? $produk->getCollection()
        : collect($produk ?? []);

    $kategoriList = collect($kategoriOptions ?? ['Coffee', 'Non-Coffee', 'Snack', 'Pastry']);
    $statusCollection = collect($statusData ?? []);
    $categoryCollection = collect($categoryData ?? []);
    $bestSellerCollection = collect($bestSellers ?? []);

    $rupiah = fn ($number) => 'Rp ' . number_format((float) $number, 0, ',', '.');
    $formatNumber = fn ($number) => number_format((float) $number, 0, ',', '.');

    $totalProduk = (int) data_get($stats ?? [], 'total_produk', 0);
    $produkTersedia = (int) data_get($stats ?? [], 'produk_tersedia', 0);
    $produkHabis = (int) data_get($stats ?? [], 'produk_habis', 0);
    $avgHarga = (float) data_get($stats ?? [], 'avg_harga', 0);

    $availablePercent = $totalProduk > 0 ? round(($produkTersedia / $totalProduk) * 100, 1) : 0;
    $outPercent = $totalProduk > 0 ? round(($produkHabis / $totalProduk) * 100, 1) : 0;

    $maxCategory = max(1, (int) $categoryCollection->max(fn ($item) => (int) data_get($item, 'count', 0)));
    $maxSold = max(1, (int) $bestSellerCollection->max(fn ($item) => (int) ($item->total_terjual ?? 0)));

    $statusMeta = [
        'tersedia' => [
            'label' => 'Tersedia',
            'icon' => 'check-circle-2',
            'tone' => '#3F7D58',
            'bg' => 'rgba(63, 125, 88, .12)',
            'border' => 'rgba(63, 125, 88, .25)',
        ],
        'habis' => [
            'label' => 'Habis',
            'icon' => 'alert-triangle',
            'tone' => '#B4533C',
            'bg' => 'rgba(180, 83, 60, .12)',
            'border' => 'rgba(180, 83, 60, .25)',
        ],
    ];

    $statusFor = function ($key) use ($statusMeta) {
        return $statusMeta[$key] ?? [
            'label' => ucfirst((string) $key),
            'icon' => 'circle',
            'tone' => '#6F4E37',
            'bg' => 'rgba(111, 78, 55, .12)',
            'border' => 'rgba(111, 78, 55, .24)',
        ];
    };

    $imageUrl = function ($item) {
        if (!$item->gambar) {
            return null;
        }
        return asset('storage/' . $item->gambar);
    };

    $statCards = [
        [
            'label' => 'Total Produk',
            'value' => $formatNumber($totalProduk),
            'desc' => 'Seluruh menu yang tercatat',
            'icon' => 'coffee',
            'tone' => '#6F4E37',
            'bg' => 'rgba(111, 78, 55, .12)',
            'border' => 'rgba(111, 78, 55, .24)',
        ],
        [
            'label' => 'Produk Tersedia',
            'value' => rtrim(rtrim(number_format($availablePercent, 1, ',', '.'), '0'), ',') . '%',
            'desc' => 'Menu yang dapat dipesan pelanggan',
            'icon' => 'check-circle-2',
            'tone' => '#3F7D58',
            'bg' => 'rgba(63, 125, 88, .12)',
            'border' => 'rgba(63, 125, 88, .25)',
        ],
        [
            'label' => 'Produk Habis',
            'value' => rtrim(rtrim(number_format($outPercent, 1, ',', '.'), '0'), ',') . '%',
            'desc' => 'Menu yang perlu diperbarui stoknya',
            'icon' => 'alert-triangle',
            'tone' => '#B4533C',
            'bg' => 'rgba(180, 83, 60, .12)',
            'border' => 'rgba(180, 83, 60, .25)',
        ],
        [
            'label' => 'Rata-rata Harga',
            'value' => $rupiah($avgHarga),
            'desc' => 'Rata-rata harga dari semua menu',
            'icon' => 'badge-dollar-sign',
            'tone' => '#C7955B',
            'bg' => 'rgba(199, 149, 91, .14)',
            'border' => 'rgba(199, 149, 91, .28)',
        ],
    ];
@endphp

<div class="product-page space-y-8">
    {{-- Hero Section --}}
    <section class="product-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.95fr] xl:items-end">
            <div>
                <span class="product-badge">
                    <i data-lucide="coffee" class="h-4 w-4"></i>
                    Product Menu
                </span>

                <h1 class="product-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">
                    Manajemen Produk Aroma Coffee
                </h1>

                <p class="product-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Kelola katalog menu, kategori, harga, gambar, status ketersediaan,
                    performa penjualan, dan rating feedback dari pelanggan.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="button"
                            onclick="document.getElementById('createProductDialog').showModal()"
                            class="inline-flex items-center gap-2 rounded-full bg-[#3B271D] px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Tambah Produk
                    </button>

                    <a href="{{ route('transaksi.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="receipt" class="h-4 w-4"></i>
                        Transaksi
                    </a>

                    <a href="{{ route('feedback.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="message-circle" class="h-4 w-4"></i>
                        Feedback
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($statCards as $card)
                    <article class="product-stat-card"
                             style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                        <div class="relative z-10">
                            <div class="mb-3 product-icon-box"
                                 style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                                <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                            </div>

                            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                                {{ $card['label'] }}
                            </p>

                            <p class="mt-2 text-2xl font-extrabold text-[#3B271D] md:text-3xl">
                                {{ $card['value'] }}
                            </p>

                            <p class="product-muted mt-2 text-xs leading-5">
                                {{ $card['desc'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Filter Section --}}
    <section class="product-panel p-5 md:p-7">
        <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="product-badge">
                    <i data-lucide="filter" class="h-4 w-4"></i>
                    Filter Produk
                </span>

                <h2 class="product-title mt-4 font-serif text-3xl font-bold">
                    Cari dan Kelompokkan Menu
                </h2>

                <p class="product-muted mt-2 text-sm leading-7">
                    Gunakan pencarian nama, kategori, deskripsi, atau status ketersediaan.
                </p>
            </div>

            @if(request()->hasAny(['search', 'kategori', 'status']))
                <a href="{{ route('produk.index') }}"
                   class="inline-flex w-fit items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                    <i data-lucide="x" class="h-4 w-4"></i>
                    Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('produk.index') }}" class="grid gap-4 lg:grid-cols-[1.4fr_.8fr_.7fr_auto] lg:items-end">
            <div>
                <label for="search" class="mb-2 block text-sm">Pencarian</label>
                <input type="search"
                       id="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama produk, kategori, atau deskripsi...">
            </div>

            <div>
                <label for="kategori" class="mb-2 block text-sm">Kategori</label>
                <select id="kategori" name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $item)
                        <option value="{{ $item }}" @selected($kategori === $item)>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm">Status</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="tersedia" @selected($status === 'tersedia')>Tersedia</option>
                    <option value="habis" @selected($status === 'habis')>Habis</option>
                </select>
            </div>

            <button type="submit" class="btn h-[2.95rem]">
                <i data-lucide="search" class="h-4 w-4"></i>
                Cari
            </button>
        </form>
    </section>

    {{-- Stats & Chart Section --}}
    <section class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
        <div class="product-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="product-badge">
                    <i data-lucide="pie-chart" class="h-4 w-4"></i>
                    Menu Distribution
                </span>

                <h2 class="product-title mt-4 font-serif text-3xl font-bold">
                    Distribusi Kategori
                </h2>

                <p class="product-muted mt-2 text-sm leading-7">
                    Komposisi menu berdasarkan kategori produk.
                </p>
            </div>

            <div class="product-chart-wrap">
                <canvas id="categoryChart"></canvas>
            </div>

            <div class="mt-5 space-y-3">
                @foreach($categoryCollection as $item)
                    @php
                        $count = (int) data_get($item, 'count', 0);
                        $width = ($count / $maxCategory) * 100;
                    @endphp

                    <div class="product-list-card">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <p class="truncate font-extrabold text-[#3B271D]">
                                {{ data_get($item, 'label', '-') }}
                            </p>

                            <p class="font-extrabold text-[#3B271D]">
                                {{ $formatNumber($count) }}
                            </p>
                        </div>

                        <div class="product-progress-track">
                            <div class="product-progress-fill"
                                 style="--tone: #6F4E37; width: {{ min(100, max(0, $width)) }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="product-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="product-badge">
                    <i data-lucide="flame" class="h-4 w-4"></i>
                    Best Seller
                </span>

                <h2 class="product-title mt-4 font-serif text-3xl font-bold">
                    Performa Produk Terjual
                </h2>

                <p class="product-muted mt-2 text-sm leading-7">
                    Produk dengan jumlah item terjual tertinggi.
                </p>
            </div>

            <div class="space-y-4">
                @forelse($bestSellerCollection as $item)
                    @php
                        $sold = (int) ($item->total_terjual ?? 0);
                        $revenue = (float) ($item->total_pendapatan ?? 0);
                        $rating = (float) ($item->rata_rating ?? 0);
                        $width = ($sold / $maxSold) * 100;
                    @endphp

                    <article class="product-list-card">
                        <div class="mb-3 flex items-start justify-between gap-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                    @if($imageUrl($item))
                                        <img src="{{ $imageUrl($item) }}"
                                             alt="{{ $item->nama_produk }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <i data-lucide="coffee" class="h-6 w-6"></i>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <p class="truncate font-extrabold text-[#3B271D]">
                                        {{ $item->nama_produk }}
                                    </p>

                                    <p class="product-muted mt-1 text-xs">
                                        {{ $item->kategori ?: 'Tanpa Kategori' }}
                                    </p>
                                </div>
                            </div>

                            <span class="product-pill shrink-0"
                                  style="--tone: #C7955B; --tone-bg: rgba(199, 149, 91, .14); --tone-border: rgba(199, 149, 91, .28);">
                                {{ $sold }} item
                            </span>
                        </div>

                        <div class="product-progress-track">
                            <div class="product-progress-fill"
                                 style="--tone: #C7955B; width: {{ min(100, max(0, $width)) }}%;"></div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="product-muted text-xs">Pendapatan</p>
                                <p class="font-extrabold text-[#3B271D]">{{ $rupiah($revenue) }}</p>
                            </div>

                            <div>
                                <p class="product-muted text-xs">Rating</p>
                                <p class="font-extrabold text-[#3B271D]">
                                    {{ $rating > 0 ? number_format($rating, 1, ',', '.') : '-' }}
                                </p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="product-empty">
                        <div class="max-w-xs px-6">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                <i data-lucide="coffee" class="h-7 w-7"></i>
                            </div>

                            <p class="font-bold text-[#3B271D]">Belum ada produk terjual</p>
                            <p class="product-muted mt-2 text-sm leading-6">
                                Produk terlaris akan muncul setelah transaksi dicatat.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Product Catalog Grid --}}
    <section class="product-panel overflow-hidden">
        <div class="border-b border-[#6F4E37]/10 p-5 md:p-7">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="product-badge">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        Product Catalog
                    </span>

                    <h2 class="product-title mt-4 font-serif text-3xl font-bold">
                        Katalog Produk
                    </h2>

                    <p class="product-muted mt-2 text-sm leading-7">
                        Daftar menu lengkap beserta status, harga, penjualan, dan feedback pelanggan.
                    </p>
                </div>

                <span class="product-pill w-fit"
                      style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                    {{ method_exists($produk, 'total') ? $formatNumber($produk->total()) : $formatNumber($produkCollection->count()) }} data
                </span>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($produk as $p)
                @php
                    $meta = $statusFor($p->status);
                    $sold = (int) ($p->total_terjual ?? 0);
                    $revenue = (float) ($p->total_pendapatan ?? 0);
                    $rating = (float) ($p->rata_rating ?? 0);
                @endphp

                <article class="product-card">
                    <div class="product-image">
                        @if($imageUrl($p))
                            <img src="{{ $imageUrl($p) }}" alt="{{ $p->nama_produk }}">
                        @else
                            <div class="product-image-placeholder">
                                <i data-lucide="coffee" class="h-14 w-14"></i>
                            </div>
                        @endif

                        <div class="absolute left-4 top-4">
                            <span class="product-pill"
                                  style="--tone: {{ $meta['tone'] }}; --tone-bg: {{ $meta['bg'] }}; --tone-border: {{ $meta['border'] }};">
                                <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5"></i>
                                {{ $meta['label'] }}
                            </span>
                        </div>

                        <div class="absolute right-4 top-4">
                            <span class="rounded-full bg-white/85 px-3 py-1.5 text-xs font-extrabold text-[#3B271D] backdrop-blur">
                                {{ $p->kategori ?: 'Menu' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="truncate text-xl font-extrabold text-[#3B271D]">
                                    {{ $p->nama_produk }}
                                </h3>

                                <p class="mt-1 text-2xl font-extrabold text-[#6F4E37]">
                                    {{ $rupiah($p->harga) }}
                                </p>
                            </div>

                            <div class="flex shrink-0 gap-2">
                                <button type="button"
                                        class="product-action"
                                        onclick="document.getElementById('editProduct{{ $p->id_produk }}').showModal()"
                                        title="Edit produk">
                                    <i data-lucide="pencil" class="h-4 w-4"></i>
                                </button>

                                <form method="POST"
                                      action="{{ route('produk.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="product-action product-danger"
                                            title="Hapus produk">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="product-clamp min-h-[3rem] text-sm leading-6 text-[#3B271D]/62">
                            {{ $p->deskripsi ?: 'Deskripsi produk belum diisi.' }}
                        </p>

                        <div class="mt-5 grid grid-cols-3 gap-2">
                            <div class="rounded-2xl bg-[#F8EFE3]/65 p-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-[#A7784D]">Terjual</p>
                                <p class="mt-1 text-lg font-extrabold text-[#3B271D]">{{ $sold }}</p>
                            </div>

                            <div class="rounded-2xl bg-[#F8EFE3]/65 p-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-[#A7784D]">Feedback</p>
                                <p class="mt-1 text-lg font-extrabold text-[#3B271D]">{{ $p->feedback_count ?? 0 }}</p>
                            </div>

                            <div class="rounded-2xl bg-[#F8EFE3]/65 p-3">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.12em] text-[#A7784D]">Rating</p>
                                <p class="mt-1 text-lg font-extrabold text-[#3B271D]">
                                    {{ $rating > 0 ? number_format($rating, 1, ',', '.') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-2xl bg-white/65 p-3">
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="product-muted">Pendapatan</span>
                                <span class="font-extrabold text-[#3B271D]">{{ $rupiah($revenue) }}</span>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="product-empty md:col-span-2 xl:col-span-3">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                            <i data-lucide="coffee" class="h-7 w-7"></i>
                        </div>

                        <p class="font-bold text-[#3B271D]">Produk tidak ditemukan</p>
                        <p class="product-muted mt-2 text-sm leading-6">
                            Tambahkan produk baru atau ubah filter pencarian.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(method_exists($produk, 'links'))
            <div class="border-t border-[#6F4E37]/10 p-5">
                {{ $produk->links() }}
            </div>
        @endif
    </section>

    {{-- Modal Create --}}
    <dialog id="createProductDialog" class="product-dialog">
        <div class="product-dialog-card">
            <div class="flex items-start justify-between gap-4 border-b border-[#6F4E37]/10 p-5 md:p-6">
                <div>
                    <span class="product-badge">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Tambah Produk
                    </span>

                    <h3 class="product-title mt-3 font-serif text-3xl font-bold">
                        Tambah Menu Baru
                    </h3>

                    <p class="product-muted mt-2 text-sm leading-6">
                        Lengkapi nama, kategori, harga, status, deskripsi, dan gambar produk.
                    </p>
                </div>

                <button type="button"
                        class="rounded-2xl bg-[#F8EFE3] p-3 text-[#6F4E37]"
                        onclick="document.getElementById('createProductDialog').close()">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data" class="p-5 md:p-6">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="nama_produk" class="mb-2 block text-sm">Nama Produk</label>
                        <input id="nama_produk"
                               type="text"
                               name="nama_produk"
                               value="{{ old('nama_produk') }}"
                               placeholder="Contoh: Caramel Latte"
                               required>
                    </div>

                    <div>
                        <label for="kategori_input" class="mb-2 block text-sm">Kategori</label>
                        <select id="kategori_input" name="kategori">
                            <option value="">Tanpa Kategori</option>
                            @foreach($kategoriList as $item)
                                <option value="{{ $item }}" @selected(old('kategori') === $item)>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="harga" class="mb-2 block text-sm">Harga</label>
                        <input id="harga"
                               type="number"
                               name="harga"
                               value="{{ old('harga') }}"
                               min="0"
                               step="100"
                               placeholder="25000"
                               required>
                    </div>

                    <div>
                        <label for="status_input" class="mb-2 block text-sm">Status</label>
                        <select id="status_input" name="status" required>
                            <option value="tersedia" @selected(old('status', 'tersedia') === 'tersedia')>Tersedia</option>
                            <option value="habis" @selected(old('status') === 'habis')>Habis</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="gambar" class="mb-2 block text-sm">Gambar Produk</label>
                        <input id="gambar" type="file" name="gambar" accept="image/*">
                        <p class="product-muted mt-2 text-xs">
                            Format gambar umum seperti JPG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="deskripsi" class="mb-2 block text-sm">Deskripsi</label>
                        <textarea id="deskripsi"
                                  name="deskripsi"
                                  rows="4"
                                  placeholder="Tuliskan deskripsi singkat produk...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button"
                            class="btn-secondary"
                            onclick="document.getElementById('createProductDialog').close()">
                        Batal
                    </button>

                    <button type="submit" class="btn">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    {{-- Modal Edit (Looping) --}}
    @foreach($produkCollection as $p)
        <dialog id="editProduct{{ $p->id_produk }}" class="product-dialog">
            <div class="product-dialog-card">
                <div class="flex items-start justify-between gap-4 border-b border-[#6F4E37]/10 p-5 md:p-6">
                    <div>
                        <span class="product-badge">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                            Edit Produk
                        </span>

                        <h3 class="product-title mt-3 font-serif text-3xl font-bold">
                            Perbarui Menu
                        </h3>

                        <p class="product-muted mt-2 text-sm leading-6">
                            Perubahan gambar bersifat opsional. Kosongkan jika tidak ingin mengganti gambar.
                        </p>
                    </div>

                    <button type="button"
                            class="rounded-2xl bg-[#F8EFE3] p-3 text-[#6F4E37]"
                            onclick="document.getElementById('editProduct{{ $p->id_produk }}').close()">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('produk.update', $p) }}" enctype="multipart/form-data" class="p-5 md:p-6">
                    @csrf
                    @method('PUT')

                    <div class="mb-5 rounded-3xl bg-[#F8EFE3]/60 p-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-white text-[#6F4E37]">
                                @if($imageUrl($p))
                                    <img src="{{ $imageUrl($p) }}"
                                         alt="{{ $p->nama_produk }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <i data-lucide="coffee" class="h-8 w-8"></i>
                                @endif
                            </div>

                            <div>
                                <p class="font-extrabold text-[#3B271D]">{{ $p->nama_produk }}</p>
                                <p class="product-muted mt-1 text-sm">{{ $p->kategori ?: 'Tanpa Kategori' }}</p>
                                <p class="mt-1 text-sm font-extrabold text-[#6F4E37]">{{ $rupiah($p->harga) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm">Nama Produk</label>
                            <input type="text"
                                   name="nama_produk"
                                   value="{{ old('nama_produk', $p->nama_produk) }}"
                                   required>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm">Kategori</label>
                            <select name="kategori">
                                <option value="">Tanpa Kategori</option>
                                @foreach($kategoriList as $item)
                                    <option value="{{ $item }}" @selected(old('kategori', $p->kategori) === $item)>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm">Harga</label>
                            <input type="number"
                                   name="harga"
                                   value="{{ old('harga', $p->harga) }}"
                                   min="0"
                                   step="100"
                                   required>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm">Status</label>
                            <select name="status" required>
                                <option value="tersedia" @selected(old('status', $p->status) === 'tersedia')>Tersedia</option>
                                <option value="habis" @selected(old('status', $p->status) === 'habis')>Habis</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm">Ganti Gambar Produk</label>
                            <input type="file" name="gambar" accept="image/*">
                            <p class="product-muted mt-2 text-xs">
                                Kosongkan jika gambar tidak ingin diganti.
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm">Deskripsi</label>
                            <textarea name="deskripsi" rows="4">{{ old('deskripsi', $p->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button"
                                class="btn-secondary"
                                onclick="document.getElementById('editProduct{{ $p->id_produk }}').close()">
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

        const categoryData = @json($categoryCollection->values());
        const categoryCanvas = document.getElementById('categoryChart');

        if (categoryCanvas && categoryData.length) {
            new Chart(categoryCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(item => item.label),
                    datasets: [{
                        data: categoryData.map(item => Number(item.count || 0)),
                        backgroundColor: ['#6F4E37', '#C7955B', '#A7784D', '#3F7D58', '#8A8077', '#5B8EA8'],
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

                                    return `${context.label}: ${value} produk (${percent}%)`;
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
