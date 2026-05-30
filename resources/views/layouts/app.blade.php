<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aroma Coffee Bland') - Customer Journey Platform</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: {
                            cream: '#F8EFE3',
                            milk: '#FDF9F3',
                            latte: '#E8D6BF',
                            caramel: '#C7955B',
                            bronze: '#A7784D',
                            mocha: '#6F4E37',
                            espresso: '#3B271D',
                            ink: '#241711',
                        },
                        success: '#3F7D58',
                        warning: '#B7791F',
                        danger: '#B4533C',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    },
                    boxShadow: {
                        soft: '0 18px 45px rgba(74, 44, 26, 0.10)',
                        card: '0 12px 35px rgba(68, 42, 25, 0.08)',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --cream: #F8EFE3;
            --milk: #FDF9F3;
            --latte: #E8D6BF;
            --caramel: #C7955B;
            --bronze: #A7784D;
            --mocha: #6F4E37;
            --espresso: #3B271D;
            --ink: #241711;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 0%, rgba(199, 149, 91, .20), transparent 28%),
                radial-gradient(circle at 90% 10%, rgba(111, 78, 55, .12), transparent 25%),
                linear-gradient(135deg, #FDF9F3 0%, #F8EFE3 45%, #EFE0CE 100%);
        }

        ::selection {
            background: var(--caramel);
            color: white;
        }

        ::-webkit-scrollbar {
            width: 9px;
            height: 9px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(232, 214, 191, .45);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(111, 78, 55, .45);
            border-radius: 999px;
        }

        .coffee-grain-bg {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .10) 0 2px, transparent 3px),
                radial-gradient(circle at 80% 30%, rgba(255, 255, 255, .07) 0 2px, transparent 3px),
                radial-gradient(circle at 30% 80%, rgba(255, 255, 255, .06) 0 2px, transparent 3px);
            background-size: 36px 36px;
        }

        .sidebar {
            background:
                linear-gradient(180deg, rgba(59, 39, 29, .98) 0%, rgba(36, 23, 17, .98) 100%);
            border-right: 1px solid rgba(255, 255, 255, .08);
        }

        .brand-mark {
            background:
                radial-gradient(circle at 30% 20%, #F8EFE3 0%, #C7955B 38%, #6F4E37 100%);
            box-shadow: 0 16px 32px rgba(0, 0, 0, .18);
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .82rem .95rem;
            border-radius: 1rem;
            color: rgba(255, 255, 255, .68);
            font-size: .875rem;
            font-weight: 600;
            transition: all .22s ease;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, .07);
            transform: translateX(3px);
        }

        .nav-link.active {
            color: #FFF7EA;
            background: linear-gradient(135deg, rgba(199, 149, 91, .32), rgba(199, 149, 91, .10));
            box-shadow: inset 0 0 0 1px rgba(248, 239, 227, .12);
        }

        .nav-link.active::before {
            content: "";
            position: absolute;
            left: -1.5rem;
            top: 50%;
            width: 5px;
            height: 58%;
            border-radius: 999px;
            background: var(--caramel);
            transform: translateY(-50%);
        }

        .topbar {
            background: rgba(253, 249, 243, .78);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(111, 78, 55, .10);
        }

        .card {
            background: rgba(253, 249, 243, .86);
            border: 1px solid rgba(111, 78, 55, .11);
            border-radius: 1.35rem;
            box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
            backdrop-filter: blur(14px);
        }

        .card-solid {
            background: #FFFBF5;
            border: 1px solid rgba(111, 78, 55, .10);
            border-radius: 1.35rem;
            box-shadow: 0 12px 35px rgba(68, 42, 25, .08);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            min-height: 2.65rem;
            padding: .72rem 1.15rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--mocha), var(--espresso));
            color: white;
            font-size: .875rem;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 12px 24px rgba(59, 39, 29, .18);
            transition: all .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(59, 39, 29, .23);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            min-height: 2.65rem;
            padding: .72rem 1.15rem;
            border-radius: 999px;
            background: rgba(111, 78, 55, .08);
            color: var(--espresso);
            border: 1px solid rgba(111, 78, 55, .12);
            font-size: .875rem;
            font-weight: 700;
            transition: all .2s ease;
        }

        .btn-secondary:hover {
            background: rgba(111, 78, 55, .13);
        }

        .input,
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        input[type="search"],
        select,
        textarea {
            width: 100%;
            border: 1px solid rgba(111, 78, 55, .16);
            border-radius: 1rem;
            background: rgba(255, 251, 245, .92);
            color: var(--ink);
            padding: .78rem .95rem;
            font-size: .9rem;
            outline: none;
            transition: all .18s ease;
        }

        input[type="checkbox"],
        input[type="radio"] {
            width: auto;
        }

        .input:focus,
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="search"]:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(199, 149, 91, .85);
            box-shadow: 0 0 0 4px rgba(199, 149, 91, .16);
            background: white;
        }

        label {
            color: rgba(36, 23, 17, .78);
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead th {
            background: rgba(232, 214, 191, .42);
            color: rgba(59, 39, 29, .82);
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .95rem 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(111, 78, 55, .10);
        }

        tbody td {
            padding: 1rem;
            border-bottom: 1px solid rgba(111, 78, 55, .08);
            color: rgba(36, 23, 17, .82);
            font-size: .9rem;
        }

        tbody tr {
            transition: background .18s ease;
        }

        tbody tr:hover {
            background: rgba(248, 239, 227, .45);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .02em;
            background: rgba(199, 149, 91, .14);
            color: var(--espresso);
        }

        .loyalty-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: .35rem .75rem;
            font-size: .72rem;
            font-weight: 800;
            color: #FFF8EC;
            background: linear-gradient(135deg, var(--caramel), var(--mocha));
            box-shadow: 0 10px 20px rgba(111, 78, 55, .18);
        }

        .cjm-step {
            position: relative;
            flex: 1;
            text-align: center;
            padding: 1rem .65rem;
            border-radius: 1rem;
            background: rgba(255, 251, 245, .64);
            border: 1px solid rgba(111, 78, 55, .10);
        }

        .cjm-step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 50%;
            right: -14%;
            width: 28%;
            height: 2px;
            background: rgba(199, 149, 91, .45);
        }

        .active-step {
            background: linear-gradient(135deg, var(--mocha), var(--espresso));
            color: white;
        }

        @media (max-width: 1023px) {
            .nav-link.active::before {
                left: -.8rem;
            }
        }
    </style>

    @stack('head')
</head>

<body class="font-sans antialiased">
    @php
        $staff = session('staff');
        $isAdmin = ($staff['type'] ?? '') === 'admin';

        $adminMenu = [
            ['Dashboard', route('dashboard'), 'dashboard', 'layout-dashboard', 'Ringkasan performa'],
            ['Pelanggan', route('pelanggan.index'), 'pelanggan.*', 'users', 'Data customer'],
            ['Produk Menu', route('produk.index'), 'produk.*', 'coffee', 'Katalog coffee'],
            ['Transaksi', route('transaksi.index'), 'transaksi.*', 'receipt', 'Riwayat penjualan'],
            ['Feedback', route('feedback.index'), 'feedback.*', 'message-circle', 'Suara pelanggan'],
            ['Journey Map', route('cjm.index'), 'cjm.*', 'map', 'Tahapan CJM'],
            ['Segmentasi', route('segmentasi.index'), 'segmentasi.*', 'pie-chart', 'Loyalitas customer'],
            ['Laporan', route('laporan.index'), 'laporan.*', 'file-text', 'Analisis manajemen'],
        ];

        $operatorMenu = [
            ['Dashboard', route('dashboard'), 'dashboard', 'layout-dashboard', 'Ringkasan aktivitas'],
            ['Kasir Transaksi', route('transaksi.create'), 'transaksi.create', 'calculator', 'Input penjualan'],
            ['Data Pelanggan', route('pelanggan.index'), 'pelanggan.*', 'users', 'Kelola customer'],
            ['Feedback', route('feedback.create'), 'feedback.create', 'mic', 'Input pengalaman'],
        ];

        $menuItems = $isAdmin ? $adminMenu : $operatorMenu;
    @endphp

    @if($staff)
        <div class="flex h-screen w-full overflow-hidden">
            <div id="sidebarBackdrop"
                 class="fixed inset-0 z-40 hidden bg-coffee-ink/55 opacity-0 backdrop-blur-sm transition-opacity lg:hidden"
                 onclick="toggleSidebar()"></div>

            <aside id="sidebar"
                   class="sidebar coffee-grain-bg fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-hidden px-6 py-6 text-white shadow-2xl transition-transform duration-300 ease-out lg:static lg:translate-x-0">

                <div class="mb-8 flex items-center gap-4">
                    <div class="brand-mark flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="Aroma Coffee"
                             class="h-full w-full object-cover"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=&quot;text-xl font-black&quot;>A</span>';">
                    </div>

                    <div class="min-w-0 flex-1">
                        <h1 class="font-serif text-3xl font-bold leading-none tracking-wide text-coffee-cream">
                            AROMA
                        </h1>
                        <p class="mt-1 text-[10px] font-bold uppercase tracking-[.22em] text-coffee-caramel">
                            Coffee Bland
                        </p>
                    </div>

                    <button type="button"
                            onclick="toggleSidebar()"
                            class="rounded-xl p-2 text-white/50 transition hover:bg-white/10 hover:text-white lg:hidden">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <div class="mb-6 rounded-3xl border border-white/10 bg-white/[.06] p-4">
                    <p class="text-[10px] font-bold uppercase tracking-[.18em] text-coffee-caramel">
                        Customer Journey
                    </p>
                    <p class="mt-2 text-sm leading-relaxed text-white/72">
                        Platform internal untuk membaca pola transaksi, kepuasan, dan loyalitas pelanggan.
                    </p>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto pr-1">
                    <p class="mb-3 px-2 text-[10px] font-bold uppercase tracking-[.20em] text-white/35">
                        Menu Utama
                    </p>

                    @foreach($menuItems as [$label, $url, $pattern, $icon, $hint])
                        <a href="{{ $url }}"
                           class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                            <i data-lucide="{{ $icon }}" class="h-5 w-5 shrink-0"></i>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate">{{ $label }}</span>
                                <span class="block truncate text-[10px] font-medium text-white/38">{{ $hint }}</span>
                            </span>
                        </a>
                    @endforeach
                </nav>

                <div class="mt-6 rounded-3xl border border-white/10 bg-white/[.06] p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-coffee-caramel/20 text-coffee-cream">
                            <i data-lucide="user" class="h-5 w-5"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-white">
                                {{ $staff['nama'] ?? 'Staff' }}
                            </p>
                            <p class="mt-1 text-[11px] font-semibold capitalize tracking-wide text-white/45">
                                {{ $staff['type'] ?? 'staff' }}
                            </p>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="rounded-xl p-2 text-white/45 transition hover:bg-white/10 hover:text-coffee-cream"
                                    title="Keluar">
                                <i data-lucide="log-out" class="h-5 w-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="topbar z-30 flex h-20 shrink-0 items-center justify-between px-4 md:px-8">
                    <div class="flex min-w-0 items-center gap-4">
                        <button type="button"
                                onclick="toggleSidebar()"
                                class="rounded-2xl border border-coffee-mocha/10 bg-white/70 p-3 text-coffee-espresso shadow-sm transition hover:bg-white lg:hidden">
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>

                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-[.20em] text-coffee-bronze">
                                Aroma Coffee Bland
                            </p>
                            <h2 class="truncate font-serif text-2xl font-bold text-coffee-espresso md:text-3xl">
                                @yield('title', 'Dashboard')
                            </h2>
                        </div>
                    </div>

                    <div class="hidden items-center gap-3 md:flex">
                        <div class="rounded-2xl border border-coffee-mocha/10 bg-white/60 px-4 py-3 text-right">
                            <p class="text-[10px] font-bold uppercase tracking-[.18em] text-coffee-bronze">
                                Hari Ini
                            </p>
                            <p class="text-sm font-bold text-coffee-espresso">
                                {{ now()->format('d M Y') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-coffee-mocha/10 bg-coffee-espresso px-4 py-3 text-white">
                            <p class="text-[10px] font-bold uppercase tracking-[.18em] text-coffee-caramel">
                                Role
                            </p>
                            <p class="text-sm font-bold capitalize">
                                {{ $staff['type'] ?? 'staff' }}
                            </p>
                        </div>
                    </div>
                </header>

                <main class="relative flex-1 overflow-y-auto px-4 py-6 md:px-8 md:py-8">
                    <div class="pointer-events-none absolute right-0 top-0 h-72 w-72 rounded-bl-full bg-coffee-caramel/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute bottom-0 left-0 h-80 w-80 rounded-tr-full bg-coffee-mocha/10 blur-3xl"></div>

                    <div class="relative mx-auto max-w-7xl">
                        @include('partials.alerts')
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    @else
        <main class="min-h-screen">
            @yield('content')
        </main>
    @endif

    <script>
        function refreshIcons() {
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (!sidebar || !backdrop) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');

            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 250);
            }
        }

        document.addEventListener('DOMContentLoaded', refreshIcons);
    </script>

    @stack('scripts')
</body>

</html>
