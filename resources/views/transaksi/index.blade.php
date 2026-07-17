@extends('layouts.app')

@section('title', 'Transaksi')

@push('head')
<style>
    .trx-page { color: #241711; }
    .trx-hero {
        position: relative; overflow: hidden; border-radius: 2rem;
        background: radial-gradient(circle at 10% 8%, rgba(199, 149, 91, .24), transparent 28%),
                    radial-gradient(circle at 90% 0%, rgba(111, 78, 55, .14), transparent 26%),
                    linear-gradient(135deg, rgba(253, 249, 243, .98), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }
    .trx-panel {
        position: relative; overflow: hidden; border-radius: 2rem;
        background: rgba(253, 249, 243, .88); border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08); backdrop-filter: blur(14px);
    }
    .trx-badge {
        display: inline-flex; align-items: center; gap: .5rem; border-radius: 999px;
        padding: .45rem .85rem; font-size: .68rem; font-weight: 800; letter-spacing: .12em;
        text-transform: uppercase; color: #6F4E37; background: rgba(199, 149, 91, .13);
        border: 1px solid rgba(199, 149, 91, .20);
    }
    .trx-title { color: #3B271D; letter-spacing: -.02em; }
    .trx-muted { color: rgba(36, 23, 17, .58); }
    .trx-stat-card {
        position: relative; overflow: hidden; border-radius: 1.35rem;
        background: rgba(255, 251, 245, .76); border: 1px solid rgba(111, 78, 55, .10); padding: 1.15rem;
    }
    .trx-stat-card::after {
        content: ""; position: absolute; right: -2.25rem; top: -2.25rem;
        width: 6.5rem; height: 6.5rem; border-radius: 999px; background: var(--tone-bg);
    }
    .trx-icon-box {
        display: inline-flex; align-items: center; justify-content: center; width: 2.85rem;
        height: 2.85rem; border-radius: 1rem; color: var(--tone); background: var(--tone-bg);
        border: 1px solid var(--tone-border);
    }
    .trx-pill {
        display: inline-flex; align-items: center; gap: .38rem; border-radius: 999px;
        padding: .36rem .68rem; font-size: .68rem; font-weight: 900; letter-spacing: .06em;
        text-transform: uppercase; color: var(--tone); background: var(--tone-bg);
        border: 1px solid var(--tone-border);
    }
    .trx-chart-wrap { position: relative; min-height: 310px; }
    .trx-mini-chart { position: relative; min-height: 260px; }
    .trx-list-card {
        border-radius: 1.25rem; background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .09); padding: 1rem;
    }
    .trx-progress-track {
        height: .62rem; overflow: hidden; border-radius: 999px; background: rgba(232, 214, 191, .62);
    }
    .trx-progress-fill { height: 100%; border-radius: 999px; background: var(--tone); }
    .trx-table-wrap { overflow-x: auto; }
    .trx-table { min-width: 980px; width: 100%; border-collapse: separate; border-spacing: 0; }
    .trx-table thead th {
        background: rgba(232, 214, 191, .45); color: rgba(59, 39, 29, .82); font-size: .7rem;
        font-weight: 900; letter-spacing: .08em; text-transform: uppercase; padding: .95rem 1rem;
        text-align: left; border-bottom: 1px solid rgba(111, 78, 55, .10); white-space: nowrap;
    }
    .trx-table tbody td {
        padding: 1rem; border-bottom: 1px solid rgba(111, 78, 55, .08);
        color: rgba(36, 23, 17, .82); font-size: .875rem; vertical-align: top;
    }
    .trx-table tbody tr:hover { background: rgba(248, 239, 227, .42); }
    .trx-action {
        display: inline-flex; align-items: center; justify-content: center; width: 2.35rem;
        height: 2.35rem; border-radius: .9rem; border: 1px solid rgba(111, 78, 55, .10);
        background: rgba(255, 255, 255, .72); color: #6F4E37; transition: all .18s ease;
    }
    .trx-action:hover { background: linear-gradient(135deg, var(--caramel), var(--mocha)); color: white; }
    .trx-danger:hover { background: #B4533C; color: white; }
    .trx-mobile-card {
        border-radius: 1.35rem; background: rgba(255, 255, 255, .82); border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07); padding: 1rem;
    }
    .trx-empty {
        display: flex; min-height: 240px; align-items: center; justify-content: center;
        border-radius: 1.5rem; background: rgba(248, 239, 227, .42);
        border: 1px dashed rgba(111, 78, 55, .22); text-align: center;
    }
    .trx-dialog {
        width: min(820px, calc(100% - 2rem)); border: 0; border-radius: 1.7rem;
        padding: 0; background: transparent;
    }
    .trx-dialog::backdrop { background: rgba(36, 23, 17, .55); backdrop-filter: blur(6px); }
    .trx-dialog-card {
        background: #FDF9F3; border: 1px solid rgba(111, 78, 55, .12);
        border-radius: 1.7rem; box-shadow: 0 24px 60px rgba(36, 23, 17, .28); overflow: hidden;
    }
    @media (max-width: 640px) {
        .trx-hero, .trx-panel { border-radius: 1.5rem; }
        .trx-chart-wrap { min-height: 260px; }
    }
</style>
@endpush

@section('content')
@php
    $isAdmin = session('staff.type') === 'admin';
    $isOperator = session('staff.type') === 'operator';

    $trxCollection = method_exists($transaksi, 'getCollection') ? $transaksi->getCollection() : collect($transaksi ?? []);
    $payments = collect($paymentData ?? []);
    $trend = collect($trendData ?? []);
    $products = collect($topProducts ?? []);

    $rupiah = fn ($number) => 'Rp ' . number_format((float) $number, 0, ',', '.');
    $formatNumber = fn ($number) => number_format((float) $number, 0, ',', '.');

    $monthlyRevenue = (float) data_get($stats ?? [], 'monthly_revenue', 0);
    $monthlyTransactions = (int) data_get($stats ?? [], 'monthly_transactions', 0);
    $todayRevenue = (float) data_get($stats ?? [], 'today_revenue', 0);
    $todayTransactions = (int) data_get($stats ?? [], 'today_transactions', 0);
    $avgOrderValue = (float) data_get($stats ?? [], 'avg_order_value', 0);
    $uniqueCustomers = (int) data_get($stats ?? [], 'unique_customers', 0);

    $maxPayment = max(1, (int) $payments->max(fn ($item) => (int) data_get($item, 'count', 0)));
    $maxProductQty = max(1, (int) $products->max(fn ($item) => (int) data_get($item, 'qty', 0)));

    $paymentTone = function ($method) {
        return match (strtolower((string) $method)) {
            'cash' => '#3F7D58',
            'qris' => '#6F4E37',
            'transfer' => '#5B8EA8',
            default => '#C7955B',
        };
    };

    $statCards = [
        ['label' => 'Omzet Bulan Ini', 'value' => $rupiah($monthlyRevenue), 'desc' => 'Total nilai transaksi periode berjalan', 'icon' => 'wallet', 'tone' => '#6F4E37', 'bg' => 'rgba(111, 78, 55, .12)', 'border' => 'rgba(111, 78, 55, .24)'],
        ['label' => 'Transaksi Bulan Ini', 'value' => $formatNumber($monthlyTransactions), 'desc' => 'Jumlah transaksi yang sudah tercatat', 'icon' => 'receipt', 'tone' => '#C7955B', 'bg' => 'rgba(199, 149, 91, .14)', 'border' => 'rgba(199, 149, 91, .28)'],
        ['label' => 'Omzet Hari Ini', 'value' => $rupiah($todayRevenue), 'desc' => $todayTransactions . ' transaksi hari ini', 'icon' => 'calendar-days', 'tone' => '#3F7D58', 'bg' => 'rgba(63, 125, 88, .12)', 'border' => 'rgba(63, 125, 88, .25)'],
        ['label' => 'Rata-rata Order', 'value' => $rupiah($avgOrderValue), 'desc' => $uniqueCustomers . ' pelanggan unik bulan ini', 'icon' => 'bar-chart-3', 'tone' => '#5B8EA8', 'bg' => 'rgba(91, 142, 168, .12)', 'border' => 'rgba(91, 142, 168, .25)'],
    ];
@endphp

<div class="trx-page space-y-8">
    <section class="trx-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.95fr] xl:items-end">
            <div>
                <span class="trx-badge"><i data-lucide="receipt" class="h-4 w-4"></i> Sales Transaction</span>
                <h1 class="trx-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">Riwayat Transaksi Aroma Coffee</h1>
                <p class="trx-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Halaman ini digunakan untuk memantau penjualan, metode pembayaran, omzet harian, produk terjual, dan detail transaksi pelanggan.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    @if($isOperator)
                        <a href="{{ route('transaksi.create') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-coffee-caramel to-coffee-mocha px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                            <i data-lucide="plus" class="h-4 w-4"></i> Input Transaksi
                        </a>
                    @endif
                    <a href="{{ route('pelanggan.index') }}" class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="users" class="h-4 w-4"></i> Pelanggan
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
                    <article class="trx-stat-card" style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                        <div class="relative z-10">
                            <div class="mb-3 trx-icon-box" style="--tone: {{ $card['tone'] }}; --tone-bg: {{ $card['bg'] }}; --tone-border: {{ $card['border'] }};">
                                <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">{{ $card['label'] }}</p>
                            <p class="mt-2 text-2xl font-extrabold text-[#3B271D] md:text-3xl">{{ $card['value'] }}</p>
                            <p class="trx-muted mt-2 text-xs leading-5">{{ $card['desc'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="trx-panel p-5 md:p-7">
        <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="trx-badge"><i data-lucide="filter" class="h-4 w-4"></i> Filter Transaksi</span>
                <h2 class="trx-title mt-4 font-serif text-3xl font-bold">Cari Riwayat Penjualan</h2>
                <p class="trx-muted mt-2 text-sm leading-7">Cari berdasarkan ID transaksi, pelanggan, operator, metode pembayaran, catatan, atau tanggal.</p>
            </div>
            @if(request()->hasAny(['search', 'metode_bayar', 'date_from', 'date_to']))
                <a href="{{ route('transaksi.index') }}" class="inline-flex w-fit items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                    <i data-lucide="x" class="h-4 w-4"></i> Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('transaksi.index') }}" class="grid gap-4 xl:grid-cols-[1.35fr_.75fr_.65fr_.65fr_auto] xl:items-end">
            <div>
                <label for="search" class="mb-2 block text-sm">Pencarian</label>
                <input type="search" id="search" name="search" value="{{ $search }}" placeholder="Cari ID, pelanggan, operator, atau catatan...">
            </div>
            <div>
                <label for="metode_bayar" class="mb-2 block text-sm">Metode Bayar</label>
                <select id="metode_bayar" name="metode_bayar">
                    <option value="">Semua</option>
                    @foreach($paymentOptions as $option)
                        <option value="{{ $option }}" @selected($metode === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="mb-2 block text-sm">Dari Tanggal</label>
                <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div>
                <label for="date_to" class="mb-2 block text-sm">Sampai Tanggal</label>
                <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}">
            </div>
            <button type="submit" class="btn h-[2.95rem]"><i data-lucide="search" class="h-4 w-4"></i> Cari</button>
        </form>
    </section>

    <!--<section class="grid gap-6 xl:grid-cols-[1.45fr_.8fr]">
        <div class="trx-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="trx-badge"><i data-lucide="activity" class="h-4 w-4"></i> Sales Trend</span>
                <h2 class="trx-title mt-4 font-serif text-3xl font-bold">Tren Transaksi Bulan Ini</h2>
            </div>
            @if($trend->isNotEmpty())
                <div class="trx-chart-wrap"><canvas id="trendChart"></canvas></div>
            @else
                <div class="trx-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]"><i data-lucide="activity" class="h-7 w-7"></i></div>
                        <p class="font-bold text-[#3B271D]">Belum ada tren transaksi</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="trx-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="trx-badge"><i data-lucide="credit-card" class="h-4 w-4"></i> Payment Mix</span>
                <h2 class="trx-title mt-4 font-serif text-3xl font-bold">Metode Pembayaran</h2>
            </div>
            @if($payments->isNotEmpty())
                <div class="trx-mini-chart"><canvas id="paymentChart"></canvas></div>
                <div class="mt-5 space-y-3">
                    @foreach($payments as $payment)
                        @php
                            $count = (int) data_get($payment, 'count', 0);
                            $width = ($count / $maxPayment) * 100;
                            $tone = $paymentTone(data_get($payment, 'name'));
                        @endphp
                        <div class="trx-list-card">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <p class="font-extrabold text-[#3B271D]">{{ data_get($payment, 'name', '-') }}</p>
                                <p class="font-extrabold text-[#3B271D]">{{ $count }}</p>
                            </div>
                            <div class="trx-progress-track">
                                <div class="trx-progress-fill" style="--tone: {{ $tone }}; width: {{ min(100, max(0, $width)) }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="trx-empty">
                    <div class="max-w-xs px-6">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]"><i data-lucide="credit-card" class="h-7 w-7"></i></div>
                        <p class="font-bold text-[#3B271D]">Belum ada data pembayaran</p>
                    </div>
                </div>
            @endif
        </div>
    </section>-->

    <section class="trx-panel overflow-hidden">
        <div class="border-b border-[#6F4E37]/10 p-5 md:p-7">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="trx-badge"><i data-lucide="database" class="h-4 w-4"></i> Transaction Records</span>
                    <h2 class="trx-title mt-4 font-serif text-3xl font-bold">Data Transaksi</h2>
                </div>
                <span class="trx-pill w-fit" style="--tone: #6F4E37; --tone-bg: rgba(111, 78, 55, .12); --tone-border: rgba(111, 78, 55, .24);">
                    {{ method_exists($transaksi, 'total') ? $formatNumber($transaksi->total()) : $formatNumber($trxCollection->count()) }} data
                </span>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="trx-table-wrap">
                <table class="trx-table">
                    <thead>
                        <tr>
                            <th>ID</th> <th>Tanggal</th> <th>Pelanggan</th> <th>Operator</th> <th>Metode</th> <th>Item</th> <th class="text-right">Total</th> <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                            @php
                                $itemCount = $t->detail->sum('jumlah');
                                $paymentColor = $paymentTone($t->metode_bayar);
                            @endphp
                            <tr>
                                <td><span class="font-extrabold text-[#3B271D]">#{{ $t->id_transaksi }}</span></td>
                                <td>
                                    <div class="font-bold text-[#3B271D]">{{ optional($t->tanggal_transaksi)->format('d M Y') }}</div>
                                    <div class="trx-muted mt-1 text-xs">{{ optional($t->tanggal_transaksi)->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <div class="font-bold text-[#3B271D]">{{ optional($t->pelanggan)->nama ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-[#3B271D]">{{ optional($t->operator)->nama ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="trx-pill" style="--tone: {{ $paymentColor }}; --tone-bg: rgba(199, 149, 91, .13); --tone-border: rgba(199, 149, 91, .24);">
                                        {{ $t->metode_bayar ?: 'Tidak dicatat' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-extrabold text-[#3B271D]">{{ $itemCount }} item</div>
                                </td>
                                <td class="text-right">
                                    <span class="font-extrabold text-[#3B271D]">{{ $rupiah($t->total_harga) }}</span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" class="trx-action" onclick="document.getElementById('detailTransaksi{{ $t->id_transaksi }}').showModal()" title="Detail transaksi">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                        @if($isAdmin)
                                            <form method="POST" action="{{ route('transaksi.destroy', $t) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="trx-action trx-danger" title="Hapus transaksi"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8"><div class="py-10 text-center"><p class="font-bold text-[#3B271D]">Transaksi tidak ditemukan</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($transaksi, 'links'))
            <div class="border-t border-[#6F4E37]/10 p-5">
                {{ $transaksi->links() }}
            </div>
        @endif
    </section>

    @foreach($trxCollection as $t)
        <dialog id="detailTransaksi{{ $t->id_transaksi }}" class="trx-dialog">
            <div class="trx-dialog-card">
                <div class="flex items-start justify-between gap-4 border-b border-[#6F4E37]/10 p-5 md:p-6">
                    <div>
                        <span class="trx-badge"><i data-lucide="receipt-text" class="h-4 w-4"></i> Detail Transaksi</span>
                        <h3 class="trx-title mt-3 font-serif text-3xl font-bold">Transaksi #{{ $t->id_transaksi }}</h3>
                    </div>
                    <button type="button" class="rounded-2xl bg-[#F8EFE3] p-3 text-[#6F4E37]" onclick="document.getElementById('detailTransaksi{{ $t->id_transaksi }}').close()">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <div class="p-5 md:p-6">
                    <div class="overflow-hidden rounded-3xl border border-[#6F4E37]/10">
                        <table class="w-full">
                            <thead><tr><th>Produk</th> <th class="text-right">Harga</th> <th class="text-right">Qty</th> <th class="text-right">Subtotal</th></tr></thead>
                            <tbody>
                                @foreach($t->detail as $detail)
                                    <tr>
                                        <td><div class="font-extrabold text-[#3B271D]">{{ optional($detail->produk)->nama_produk ?? 'Produk' }}</div></td>
                                        <td class="text-right">{{ $rupiah($detail->harga_satuan) }}</td>
                                        <td class="text-right">{{ $detail->jumlah }}</td>
                                        <td class="text-right font-extrabold text-[#3B271D]">{{ $rupiah($detail->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(filled($t->catatan))
                        <div class="mt-5 rounded-3xl border border-[#6F4E37]/10 bg-[#F8EFE3] p-5">
                            <p class="mb-2 text-xs font-extrabold uppercase tracking-[.14em] text-[#A7784D]">
                                Catatan Transaksi
                            </p>

                            <p class="whitespace-pre-line text-sm leading-7 text-[#3B271D]">
                                {{ filled($t->catatan) ? $t->catatan : 'Tidak ada catatan.' }}
                            </p>
                        </div>
                    @endif
                    <div class="mt-5 flex items-center justify-between rounded-3xl bg-gradient-to-br from-coffee-caramel to-coffee-mocha p-5 text-white">
                        <span class="text-sm font-bold uppercase tracking-[.14em] text-[#C7955B]">Total Pembayaran</span>
                        <span class="text-2xl font-extrabold">{{ $rupiah($t->total_harga) }}</span>
                    </div>
                </div>
            </div>
        </dialog>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons();
        if (typeof Chart === 'undefined') return;

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = 'rgba(59, 39, 29, .62)';

        const trendData = @json($trend->values());
        const paymentData = @json($payments->values());

        const rupiah = function (value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
        };

        const trendCanvas = document.getElementById('trendChart');
        if (trendCanvas && trendData.length) {
            const ctx = trendCanvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 310);
            gradient.addColorStop(0, 'rgba(111, 78, 55, .85)');
            gradient.addColorStop(1, 'rgba(199, 149, 91, .22)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: trendData.map(item => item.label),
                    datasets: [
                        { label: 'Transaksi', data: trendData.map(item => Number(item.count || 0)), backgroundColor: gradient, borderRadius: 14 },
                        { type: 'line', label: 'Omzet', data: trendData.map(item => Number(item.total || 0)), borderColor: '#C7955B', borderWidth: 3, tension: .35, yAxisID: 'y1' }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        const paymentCanvas = document.getElementById('paymentChart');
        if (paymentCanvas && paymentData.length) {
            new Chart(paymentCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: paymentData.map(item => item.name),
                    datasets: [{ data: paymentData.map(item => Number(item.count || 0)), backgroundColor: ['#6F4E37', '#C7955B', '#3F7D58', '#5B8EA8', '#8A8077'], borderWidth: 5 }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false } } }
            });
        }
    });
</script>
@endpush
