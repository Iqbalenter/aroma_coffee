@extends('layouts.app')

@section('title', 'Input Transaksi')

@push('head')
<style>
    .cashier-page {
        color: #241711;
    }

    .cashier-hero {
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

    .cashier-panel {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    .cashier-badge {
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

    .cashier-title {
        color: #3B271D;
        letter-spacing: -.02em;
    }

    .cashier-muted {
        color: rgba(36, 23, 17, .58);
    }

    .product-search {
        position: sticky;
        top: 0;
        z-index: 20;
        background: rgba(253, 249, 243, .92);
        backdrop-filter: blur(16px);
    }

    .cashier-product-card {
        overflow: hidden;
        border-radius: 1.45rem;
        background: rgba(255, 255, 255, .82);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 30px rgba(68, 42, 25, .07);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .cashier-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 38px rgba(68, 42, 25, .12);
    }

    .cashier-product-image {
        height: 9.5rem;
        overflow: hidden;
        background:
            radial-gradient(circle at 30% 20%, rgba(199, 149, 91, .24), transparent 34%),
            linear-gradient(135deg, #F8EFE3, #E8D6BF);
    }

    .cashier-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cashier-image-placeholder {
        display: flex;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #6F4E37;
    }

    .qty-btn {
        display: inline-flex;
        width: 2.35rem;
        height: 2.35rem;
        align-items: center;
        justify-content: center;
        border-radius: .9rem;
        background: linear-gradient(135deg, var(--caramel), var(--mocha));
        color: white;
        font-weight: 900;
        transition: all .18s ease;
    }

    .qty-btn:hover {
        transform: translateY(-1px);
        background: #241711;
    }

    .qty-btn.minus {
        background: rgba(111, 78, 55, .10);
        color: #3B271D;
    }

    .qty-number {
        display: inline-flex;
        min-width: 2.4rem;
        height: 2.35rem;
        align-items: center;
        justify-content: center;
        border-radius: .9rem;
        background: #F8EFE3;
        color: #3B271D;
        font-weight: 900;
    }

    .order-card {
        border-radius: 1.25rem;
        background: rgba(255, 251, 245, .82);
        border: 1px solid rgba(111, 78, 55, .09);
        padding: 1rem;
    }

    .order-summary {
        position: sticky;
        top: 1.25rem;
    }

    .empty-cart {
        display: flex;
        min-height: 180px;
        align-items: center;
        justify-content: center;
        border-radius: 1.25rem;
        border: 1px dashed rgba(111, 78, 55, .22);
        background: rgba(248, 239, 227, .42);
        text-align: center;
    }

    @media (max-width: 1279px) {
        .order-summary {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .cashier-hero,
        .cashier-panel {
            border-radius: 1.5rem;
        }

        .cashier-product-image {
            height: 8.5rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $productCollection = collect($produk ?? []);
    $customerCollection = collect($pelanggan ?? []);

    $rupiah = fn ($number) => 'Rp ' . number_format((float) $number, 0, ',', '.');

    $imageUrl = function ($item) {
        if (!$item->gambar) {
            return null;
        }

        $path = ltrim($item->gambar, '/');

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (\Illuminate\Support\Str::startsWith($path, 'public/')) {
            return asset('storage/' . \Illuminate\Support\Str::after($path, 'public/'));
        }

        return asset('storage/' . $path);
    };

    $productPayload = $productCollection->map(fn ($item) => [
        'id' => $item->id_produk,
        'name' => $item->nama_produk,
        'category' => $item->kategori ?: 'Menu',
        'price' => (float) $item->harga,
    ])->values();
@endphp

<div class="cashier-page space-y-8">
    <section class="cashier-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.85fr] xl:items-end">
            <div>
                <span class="cashier-badge">
                    <i data-lucide="calculator" class="h-4 w-4"></i>
                    Cashier Mode
                </span>

                <h1 class="cashier-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">
                    Input Transaksi Baru
                </h1>

                <p class="cashier-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Pilih pelanggan, metode pembayaran, dan menu yang dibeli. Sistem akan menghitung
                    total transaksi secara otomatis sebelum disimpan.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-coffee-caramel to-coffee-mocha px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        Kembali ke Dashboard
                    </a>

                    <a href="{{ route('pelanggan.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        Data Pelanggan
                    </a>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-[#6F4E37]/10 bg-white/55 p-5">
                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">
                    Operator
                </p>

                <h2 class="mt-2 font-serif text-3xl font-bold text-[#3B271D]">
                    {{ session('staff.nama') }}
                </h2>

                <p class="cashier-muted mt-3 text-sm leading-7">
                    Transaksi akan tercatat atas nama operator yang sedang login.
                </p>
            </div>
        </div>
    </section>

    <form id="transactionForm" method="POST" action="{{ route('transaksi.store') }}">
        @csrf

        <div id="hiddenCartInputs"></div>

        <div class="grid gap-6 xl:grid-cols-[1fr_390px]">
            <section class="cashier-panel overflow-hidden">
                <div class="border-b border-[#6F4E37]/10 p-5 md:p-7">
                    <div class="mb-5">
                        <span class="cashier-badge">
                            <i data-lucide="coffee" class="h-4 w-4"></i>
                            Pilih Produk
                        </span>

                        <h2 class="cashier-title mt-4 font-serif text-3xl font-bold">
                            Menu Tersedia
                        </h2>

                        <p class="cashier-muted mt-2 text-sm leading-7">
                            Tekan tombol plus atau minus untuk mengatur jumlah pesanan.
                        </p>
                    </div>

                    <div class="product-search rounded-3xl border border-[#6F4E37]/10 p-3 bg-white">
                        <div class="relative flex items-center">
                            <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#A7784D]"></i>

                            <input
                                type="text"
                                id="productSearch"
                                class="w-full bg-transparent border-0 focus:ring-0 focus:outline-none"
                                style="padding-left: 3rem; padding-right: 1rem;"
                                placeholder="Cari nama produk atau kategori..."
                            >
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 p-5 md:grid-cols-2 2xl:grid-cols-3">
                    @forelse($productCollection as $p)
                        <article class="cashier-product-card"
                                 data-product-card
                                 data-product-name="{{ strtolower($p->nama_produk . ' ' . $p->kategori) }}">
                            <div class="cashier-product-image">
                                @if($imageUrl($p))
                                    <img src="{{ $imageUrl($p) }}" alt="{{ $p->nama_produk }}">
                                @else
                                    <div class="cashier-image-placeholder">
                                        <i data-lucide="coffee" class="h-12 w-12"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-5">
                                <div class="mb-4">
                                    <span class="cashier-badge">
                                        {{ $p->kategori ?: 'Menu' }}
                                    </span>

                                    <h3 class="mt-3 truncate text-lg font-extrabold text-[#3B271D]">
                                        {{ $p->nama_produk }}
                                    </h3>

                                    <p class="mt-1 text-xl font-extrabold text-[#6F4E37]">
                                        {{ $rupiah($p->harga) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <button type="button"
                                            class="qty-btn minus"
                                            data-action="decrease"
                                            data-id="{{ $p->id_produk }}">
                                        −
                                    </button>

                                    <span id="qty-{{ $p->id_produk }}" class="qty-number">0</span>

                                    <button type="button"
                                            class="qty-btn"
                                            data-action="increase"
                                            data-id="{{ $p->id_produk }}">
                                        +
                                    </button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="empty-cart md:col-span-2 2xl:col-span-3">
                            <div class="max-w-xs px-6">
                                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                    <i data-lucide="coffee" class="h-7 w-7"></i>
                                </div>

                                <p class="font-bold text-[#3B271D]">Belum ada produk tersedia</p>
                                <p class="cashier-muted mt-2 text-sm leading-6">
                                    Transaksi baru dapat dibuat setelah produk tersedia.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>

            <aside class="order-summary space-y-6">
                <section class="cashier-panel p-5 md:p-6">
                    <div class="mb-5">
                        <span class="cashier-badge">
                            <i data-lucide="user-round" class="h-4 w-4"></i>
                            Pelanggan
                        </span>

                        <h2 class="cashier-title mt-4 font-serif text-3xl font-bold">
                            Detail Pembelian
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="id_pelanggan" class="mb-2 block text-sm">Pelanggan</label>
                            <select id="id_pelanggan" name="id_pelanggan" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($customerCollection as $c)
                                    <option value="{{ $c->id_pelanggan }}" @selected(old('id_pelanggan') == $c->id_pelanggan)>
                                        {{ $c->nama }}{{ $c->nomor_hp ? ' · ' . $c->nomor_hp : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="metode_bayar" class="mb-2 block text-sm">Metode Bayar</label>
                            <select id="metode_bayar" name="metode_bayar" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="Cash" @selected(old('metode_bayar') === 'Cash')>Cash</option>
                                <option value="QRIS" @selected(old('metode_bayar') === 'QRIS')>QRIS</option>
                                <option value="Transfer" @selected(old('metode_bayar') === 'Transfer')>Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label for="catatan" class="mb-2 block text-sm">Catatan</label>
                            <textarea id="catatan"
                                      name="catatan"
                                      rows="3"
                                      placeholder="Contoh: less ice, take away, meja 3...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="cashier-panel p-5 md:p-6">
                    <div class="mb-5 flex items-start justify-between gap-3">
                        <div>
                            <span class="cashier-badge">
                                <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                                Order Summary
                            </span>

                            <h2 class="cashier-title mt-4 font-serif text-3xl font-bold">
                                Keranjang
                            </h2>
                        </div>

                        <button type="button"
                                id="clearCartBtn"
                                class="rounded-full border border-[#6F4E37]/15 bg-white/65 px-3 py-2 text-xs font-extrabold text-[#6F4E37]">
                            Reset
                        </button>
                    </div>

                    <div id="cartItems" class="space-y-3">
                        <div class="empty-cart">
                            <div class="max-w-xs px-6">
                                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                    <i data-lucide="shopping-bag" class="h-7 w-7"></i>
                                </div>

                                <p class="font-bold text-[#3B271D]">Keranjang masih kosong</p>
                                <p class="cashier-muted mt-2 text-sm leading-6">
                                    Pilih produk dari daftar menu.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 rounded-3xl bg-gradient-to-br from-coffee-caramel to-coffee-mocha p-5 text-white">
                        <div class="mb-3 flex items-center justify-between text-sm">
                            <span class="font-bold uppercase tracking-[.14em] text-[#C7955B]">Total Item</span>
                            <span id="totalItems" class="font-extrabold">0</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="font-bold uppercase tracking-[.14em] text-[#C7955B]">Total</span>
                            <span id="grandTotal" class="text-2xl font-extrabold">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn mt-5 w-full">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Simpan Transaksi
                    </button>
                </section>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }

        const products = @json($productPayload);
        const cart = {};
        const form = document.getElementById('transactionForm');
        const cartItems = document.getElementById('cartItems');
        const hiddenCartInputs = document.getElementById('hiddenCartInputs');
        const grandTotal = document.getElementById('grandTotal');
        const totalItems = document.getElementById('totalItems');
        const clearCartBtn = document.getElementById('clearCartBtn');
        const productSearch = document.getElementById('productSearch');

        const rupiah = function (value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value || 0);
        };

        const escapeHtml = function (value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        };

        const findProduct = function (id) {
            return products.find(product => String(product.id) === String(id));
        };

        const selectedItems = function () {
            return Object.entries(cart)
                .filter(([, qty]) => Number(qty) > 0)
                .map(([id, qty]) => ({
                    product: findProduct(id),
                    qty: Number(qty)
                }))
                .filter(item => item.product);
        };

        const updateQtyDisplay = function (id) {
            const el = document.getElementById('qty-' + id);

            if (el) {
                el.textContent = cart[id] || 0;
            }
        };

        const renderCart = function () {
            const items = selectedItems();
            let total = 0;
            let qtyTotal = 0;

            hiddenCartInputs.innerHTML = '';

            if (!items.length) {
                cartItems.innerHTML = `
                    <div class="empty-cart">
                        <div class="max-w-xs px-6">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#F8EFE3] text-[#6F4E37]">
                                <i data-lucide="shopping-bag" class="h-7 w-7"></i>
                            </div>
                            <p class="font-bold text-[#3B271D]">Keranjang masih kosong</p>
                            <p class="cashier-muted mt-2 text-sm leading-6">Pilih produk dari daftar menu.</p>
                        </div>
                    </div>
                `;
            } else {
                cartItems.innerHTML = items.map((item, index) => {
                    const subtotal = item.product.price * item.qty;
                    total += subtotal;
                    qtyTotal += item.qty;

                    hiddenCartInputs.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="items[${index}][id_produk]" value="${item.product.id}">
                        <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
                    `);

                    return `
                        <div class="order-card">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate font-extrabold text-[#3B271D]">${escapeHtml(item.product.name)}</p>
                                    <p class="cashier-muted mt-1 text-xs">${escapeHtml(item.product.category)}</p>
                                </div>
                                <p class="shrink-0 font-extrabold text-[#6F4E37]">${rupiah(subtotal)}</p>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3 text-sm">
                                <span class="cashier-muted">${rupiah(item.product.price)} × ${item.qty}</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="qty-btn minus !h-8 !w-8" data-action="decrease" data-id="${item.product.id}">−</button>
                                    <span class="qty-number !h-8 !min-w-8">${item.qty}</span>
                                    <button type="button" class="qty-btn !h-8 !w-8" data-action="increase" data-id="${item.product.id}">+</button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            grandTotal.textContent = rupiah(total);
            totalItems.textContent = qtyTotal;

            if (window.lucide) {
                lucide.createIcons();
            }
        };

        const changeQty = function (id, diff) {
            cart[id] = Math.max(0, Number(cart[id] || 0) + diff);

            if (cart[id] === 0) {
                delete cart[id];
            }

            updateQtyDisplay(id);
            renderCart();
        };

        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-action][data-id]');

            if (!button) {
                return;
            }

            const id = button.dataset.id;
            const action = button.dataset.action;

            if (action === 'increase') {
                changeQty(id, 1);
            }

            if (action === 'decrease') {
                changeQty(id, -1);
            }
        });

        clearCartBtn.addEventListener('click', function () {
            Object.keys(cart).forEach(id => {
                delete cart[id];
                updateQtyDisplay(id);
            });

            renderCart();
        });

        productSearch.addEventListener('input', function () {
            const keyword = productSearch.value.toLowerCase().trim();

            document.querySelectorAll('[data-product-card]').forEach(card => {
                const text = card.dataset.productName || '';
                card.style.display = text.includes(keyword) ? '' : 'none';
            });
        });

        form.addEventListener('submit', function (event) {
            if (!selectedItems().length) {
                event.preventDefault();
                alert('Pilih minimal satu produk sebelum menyimpan transaksi.');
            }
        });

        renderCart();
    });
</script>
@endpush
