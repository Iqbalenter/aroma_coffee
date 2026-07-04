@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
    <div class="absolute inset-0 bg-gradient-to-br from-[#FDF9F3] via-[#F8EFE3] to-[#E8D6BF]">
        <div class="absolute left-[-8rem] top-[-8rem] h-96 w-96 rounded-full bg-coffee-caramel/30 blur-3xl"></div>
        <div class="absolute bottom-[-8rem] right-[-8rem] h-96 w-96 rounded-full bg-coffee-bronze/30 blur-3xl"></div>
    </div>

    <div class="relative grid w-full max-w-6xl overflow-hidden rounded-[2rem] border border-coffee-caramel/20 bg-white/70 shadow-2xl shadow-coffee-caramel/10 backdrop-blur-xl lg:grid-cols-[1.05fr_.95fr]">
        <section class="relative hidden overflow-hidden bg-gradient-to-b from-coffee-espresso to-coffee-mocha p-10 text-white lg:block">
            <div class="absolute inset-0 coffee-grain-bg opacity-45"></div>
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-coffee-caramel/30 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-coffee-latte/20 blur-3xl"></div>

            <div class="relative flex h-full min-h-[620px] flex-col justify-between">
                <div>
                    <div class="mb-8 flex items-center gap-4">
                        <div class="brand-mark flex h-16 w-16 items-center justify-center overflow-hidden rounded-3xl">
                            <img src="{{ asset('images/logo.png') }}"
                                 alt="Aroma Coffee"
                                 class="h-full w-full object-cover"
                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=&quot;text-2xl font-black&quot;>A</span>';">
                        </div>

                        <div>
                            <h1 class="font-serif text-4xl font-bold leading-none">AROMA</h1>
                            <p class="mt-1 text-[11px] font-bold uppercase tracking-[.22em] text-coffee-caramel">
                                Coffee Bland
                            </p>
                        </div>
                    </div>

                    <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[.16em] text-coffee-cream">
                        <i data-lucide="sparkles" class="h-4 w-4 text-coffee-caramel"></i>
                        Customer Journey Mapping
                    </p>

                    <h2 class="mt-8 max-w-lg font-serif text-6xl font-bold leading-[.95]">
                        Memahami pelanggan dari setiap cangkir kopi.
                    </h2>

                    <p class="mt-6 max-w-md text-sm leading-7 text-white/68">
                        Sistem ini membantu Aroma Coffee Bland membaca transaksi, feedback,
                        sentimen, segmentasi pelanggan, dan potensi loyalitas dalam satu platform.
                    </p>
                </div>
            </div>
        </section>

        <section class="p-6 md:p-10">
            <div class="mx-auto flex min-h-[620px] max-w-md flex-col justify-center">
                <div class="mb-8 text-center lg:text-left">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-coffee-caramel to-coffee-mocha text-white shadow-card lg:mx-0">
                        <i data-lucide="coffee" class="h-8 w-8"></i>
                    </div>

                    <p class="text-[11px] font-extrabold uppercase tracking-[.20em] text-coffee-bronze">
                        Aroma Coffee Bland
                    </p>

                    <h1 class="mt-3 font-serif text-4xl font-bold text-coffee-espresso">
                        Masuk ke Sistem
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-coffee-espresso/58">
                        Gunakan akun admin atau operator untuk mengakses dashboard internal.
                    </p>
                </div>

                <div class="card-solid p-6 md:p-7">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="username" class="mb-2 block text-sm">Username</label>
                                <div class="relative">
                                    <i data-lucide="user"
                                       class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-coffee-bronze"></i>
                                    <input id="username"
                                           type="text"
                                           name="username"
                                           value="{{ old('username') }}"
                                           class="input !pl-11"
                                           placeholder="Masukkan username"
                                           autocomplete="username"
                                           required
                                           autofocus>
                                </div>
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm">Password</label>
                                <div class="relative">
                                    <i data-lucide="lock"
                                       class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-coffee-bronze"></i>
                                    <input id="password"
                                           type="password"
                                           name="password"
                                           class="input !pl-11"
                                           placeholder="Masukkan password"
                                           autocomplete="current-password"
                                           required>
                                </div>
                            </div>

                            <button type="submit" class="btn w-full">
                                <i data-lucide="log-in" class="h-5 w-5"></i>
                                Login
                            </button>
                        </form>

                    <!--<div class="mt-6 rounded-3xl bg-coffee-cream/70 p-4">
                        <p class="text-[11px] font-extrabold uppercase tracking-[.16em] text-coffee-bronze">
                            Akun Demo
                        </p>

                        <div class="mt-3 grid gap-2 text-sm text-coffee-espresso/70">
                            <div class="flex items-center justify-between gap-3">
                                <span>Admin</span>
                                <code class="rounded-full bg-white px-3 py-1 font-bold text-coffee-mocha">admin / password</code>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <span>Operator</span>
                                <code class="rounded-full bg-white px-3 py-1 font-bold text-coffee-mocha">operator / password</code>
                            </div>
                        </div>
                    </div>-->
                </div>

                <p class="mt-6 text-center text-xs leading-6 text-coffee-espresso/45">
                    © {{ date('Y') }} Aroma Coffee Bland. Customer Journey Mapping Platform.
                </p>
            </div>
        </section>
    </div>
</div>
@endsection
