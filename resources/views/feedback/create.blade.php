@extends('layouts.app')

@section('title', 'Catat Feedback')

@push('head')
<style>
    /* Styling Dasar & Hero */
    .feedback-form-page { color: #241711; }
    .feedback-form-hero {
        position: relative; overflow: hidden; border-radius: 2rem;
        background: radial-gradient(circle at 10% 8%, rgba(199, 149, 91, .24), transparent 28%),
                    radial-gradient(circle at 90% 0%, rgba(111, 78, 55, .14), transparent 26%),
                    linear-gradient(135deg, rgba(253, 249, 243, .98), rgba(248, 239, 227, .94));
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 18px 45px rgba(74, 44, 26, .10);
    }

    /* Panel Utama */
    .feedback-form-panel {
        position: relative; overflow: hidden; border-radius: 2rem;
        background: rgba(253, 249, 243, .88);
        border: 1px solid rgba(111, 78, 55, .10);
        box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        backdrop-filter: blur(14px);
    }

    /* Badges & Typography */
    .feedback-form-badge {
        display: inline-flex; align-items: center; gap: .5rem; border-radius: 999px;
        padding: .45rem .85rem; font-size: .68rem; font-weight: 800; letter-spacing: .12em;
        text-transform: uppercase; color: #6F4E37;
        background: rgba(199, 149, 91, .13); border: 1px solid rgba(199, 149, 91, .20);
    }
    .feedback-form-title { color: #3B271D; letter-spacing: -.02em; }
    .feedback-form-muted { color: rgba(36, 23, 17, .58); }

    /* Custom Rating Input */
    .feedback-rating-option input { position: absolute; opacity: 0; pointer-events: none; }
    .feedback-rating-card {
        display: flex; min-height: 4.6rem; cursor: pointer; align-items: center; justify-content: center;
        border-radius: 1.25rem; border: 1px solid rgba(111, 78, 55, .12);
        background: rgba(255, 251, 245, .76); color: rgba(59, 39, 29, .68);
        font-weight: 900; transition: all .18s ease;
    }
    .feedback-rating-option input:checked + .feedback-rating-card {
        border-color: rgba(199, 149, 91, .55); background: #3B271D; color: white;
        box-shadow: 0 14px 30px rgba(59, 39, 29, .18); transform: translateY(-2px);
    }

    /* Sidebar Cards */
    .feedback-guide-card {
        border-radius: 1.35rem; background: rgba(255, 251, 245, .76);
        border: 1px solid rgba(111, 78, 55, .09); padding: 1rem;
    }
    .feedback-guide-number {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: .85rem;
        background: #3B271D; color: white; font-size: .75rem; font-weight: 900;
    }
    .feedback-step-card {
        border-radius: 1.25rem; background: rgba(255, 251, 245, .70);
        border: 1px solid rgba(111, 78, 55, .09); padding: .95rem;
    }

    @media (max-width: 640px) {
        .feedback-form-hero, .feedback-form-panel { border-radius: 1.5rem; }
    }
</style>
@endpush

@section('content')
@php
    $kategoriList = collect($kategoriOptions ?? ['Rasa Produk', 'Pelayanan', 'Harga', 'Tempat/Suasana']);
    $isAdmin = session('staff.type') === 'admin';

    $journeyHints = [
        'awareness'     => 'Pelanggan mulai mengenal Aroma Coffee.',
        'consideration' => 'Pelanggan membandingkan menu, harga, atau suasana.',
        'purchase'      => 'Pelanggan melakukan pembelian.',
        'experience'    => 'Pelanggan merasakan produk dan layanan.',
        'retention'     => 'Pelanggan berpotensi kembali membeli.',
        'loyalty'       => 'Pelanggan menjadi pelanggan setia atau merekomendasikan brand.',
    ];
@endphp

<div class="feedback-form-page space-y-8">
    <section class="feedback-form-hero p-6 md:p-8">
        <div class="relative grid gap-7 xl:grid-cols-[1.35fr_.85fr] xl:items-end">
            <div>
                <span class="feedback-form-badge"><i data-lucide="mic" class="h-4 w-4"></i> Input Voice of Customer</span>
                <h1 class="feedback-form-title mt-5 font-serif text-4xl font-bold leading-tight md:text-5xl">Catat Feedback Pelanggan</h1>
                <p class="feedback-form-muted mt-4 max-w-3xl text-sm leading-7 md:text-base">
                    Gunakan form ini untuk mencatat rating, komentar, kategori pengalaman, dan tahap customer journey pelanggan Aroma Coffee.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    @if($isAdmin)
                        <a href="{{ route('feedback.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-[#3B271D] px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Kembali ke Data Feedback
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-[#3B271D] px-4 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Kembali ke Dashboard
                        </a>
                    @endif
                    <a href="{{ route('pelanggan.index') }}" class="inline-flex items-center gap-2 rounded-full border border-[#6F4E37]/15 bg-white/65 px-4 py-3 text-sm font-bold text-[#3B271D] transition hover:bg-white">
                        <i data-lucide="users" class="h-4 w-4"></i> Data Pelanggan
                    </a>
                </div>
            </div>
            <div class="rounded-[1.5rem] border border-[#6F4E37]/10 bg-white/55 p-5">
                <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-[#A7784D]">Tujuan Input</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-[#3B271D]">Dari komentar menjadi insight</h2>
                <p class="feedback-form-muted mt-3 text-sm leading-7">Data ini akan memengaruhi analisis customer journey, pain point, dan segmentasi pelanggan.</p>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.25fr_.75fr]">
        <div class="feedback-form-panel p-5 md:p-7">
            <div class="mb-6">
                <span class="feedback-form-badge"><i data-lucide="clipboard-pen-line" class="h-4 w-4"></i> Form Feedback</span>
                <h2 class="feedback-form-title mt-4 font-serif text-3xl font-bold">Detail Pengalaman Pelanggan</h2>
                <p class="feedback-form-muted mt-2 text-sm leading-7">Lengkapi semua field agar analisis sentimen dan customer journey lebih akurat.</p>
            </div>
            <form method="POST" action="{{ route('feedback.store') }}" class="space-y-6">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="id_pelanggan" class="mb-2 block text-sm">Pelanggan</label>
                        <select id="id_pelanggan" name="id_pelanggan" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $c)
                                <option value="{{ $c->id_pelanggan }}" @selected(old('id_pelanggan') == $c->id_pelanggan)>{{ $c->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="id_produk" class="mb-2 block text-sm">Produk</label>
                        <select id="id_produk" name="id_produk" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id_produk }}" @selected(old('id_produk') == $p->id_produk)>{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="kategori" class="mb-2 block text-sm">Kategori Feedback</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahap_journey" class="mb-2 block text-sm">Tahap Journey</label>
                        <select id="tahap_journey" name="tahap_journey" required>
                            <option value="">-- Pilih Tahap --</option>
                            @foreach(\App\Models\Feedback::TAHAPAN as $key => $label)
                                <option value="{{ $key }}" @selected(old('tahap_journey') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($isAdmin)
                        <div class="md:col-span-2">
                            <label for="status" class="mb-2 block text-sm">Status Tindak Lanjut</label>
                            <select id="status" name="status">
                                <option value="baru" @selected(old('status', 'baru') === 'baru')>Baru</option>
                                <option value="ditinjau" @selected(old('status') === 'ditinjau')>Ditinjau</option>
                                <option value="selesai" @selected(old('status') === 'selesai')>Selesai</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="status" value="baru">
                    @endif
                </div>

                <div>
                    <div class="mb-3 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                        <label class="block text-sm">Rating Pengalaman</label>
                        <p class="feedback-form-muted text-xs">1–2 = pain point, 3 = netral, 4–5 = positif</p>
                    </div>
                    <div class="grid grid-cols-5 gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="feedback-rating-option relative">
                                <input type="radio" name="rating" value="{{ $i }}" @checked((string) old('rating', '5') === (string) $i) required>
                                <span class="feedback-rating-card">
                                    <span class="flex flex-col items-center gap-1">
                                        <span class="text-xl">{{ $i }}</span>
                                        <span class="text-xs">★</span>
                                    </span>
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label for="komentar" class="mb-2 block text-sm">Komentar Pelanggan</label>
                    <textarea id="komentar" name="komentar" rows="6" placeholder="Tuliskan komentar pelanggan secara jelas..." required>{{ old('komentar') }}</textarea>
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    @if($isAdmin)
                        <a href="{{ route('feedback.index') }}" class="btn-secondary">
                            Batal
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-secondary">
                            Batal
                        </a>
                    @endif
                    <button type="submit" class="btn"><i data-lucide="save" class="h-4 w-4"></i> Simpan Feedback</button>
                </div>
            </form>
        </div>

        <aside class="space-y-6">
            <div class="feedback-form-panel p-5 md:p-6">
                <div class="mb-5">
                    <span class="feedback-form-badge"><i data-lucide="route" class="h-4 w-4"></i> Journey Guide</span>
                    <h2 class="feedback-form-title mt-4 font-serif text-3xl font-bold">Panduan Tahap</h2>
                    <p class="feedback-form-muted mt-2 text-sm leading-7">Pilih tahap sesuai posisi pelanggan saat memberikan feedback.</p>
                </div>
                <div class="space-y-3">
                    @foreach(\App\Models\Feedback::TAHAPAN as $key => $label)
                        <div class="feedback-step-card">
                            <div class="flex items-start gap-3">
                                <span class="feedback-guide-number">{{ $loop->iteration }}</span>
                                <div>
                                    <p class="font-extrabold text-[#3B271D]">{{ $label }}</p>
                                    <p class="feedback-form-muted mt-1 text-sm leading-6">{{ $journeyHints[$key] ?? 'Tahapan perjalanan pelanggan.' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!--<div class="feedback-form-panel p-5 md:p-6">
                <div class="mb-5">
                    <span class="feedback-form-badge"><i data-lucide="lightbulb" class="h-4 w-4"></i> Tips Input</span>
                    <h2 class="feedback-form-title mt-4 font-serif text-3xl font-bold">Agar Data Berguna</h2>
                </div>
                <div class="space-y-3">
                    <div class="feedback-guide-card">
                        <p class="font-extrabold text-[#3B271D]">Gunakan komentar asli</p>
                        <p class="feedback-form-muted mt-1 text-sm leading-6">Tulis komentar pelanggan dengan bahasa yang mendekati ucapan aslinya.</p>
                    </div>
                    <div class="feedback-guide-card">
                        <p class="font-extrabold text-[#3B271D]">Pilih kategori spesifik</p>
                        <p class="feedback-form-muted mt-1 text-sm leading-6">Bedakan feedback tentang rasa, pelayanan, harga, atau suasana.</p>
                    </div>
                    <div class="feedback-guide-card">
                        <p class="font-extrabold text-[#3B271D]">Rating rendah = prioritas</p>
                        <p class="feedback-form-muted mt-1 text-sm leading-6">Rating 1–2 akan terbaca sebagai pain point untuk evaluasi layanan.</p>
                    </div>
                </div>
            </div>-->
        </aside>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) { lucide.createIcons(); }
    });
</script>
@endpush
