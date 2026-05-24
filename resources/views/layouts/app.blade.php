<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aroma Coffee Bland') - CJM Data Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icon Set (Lucide/Feather) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { primary: '#5A3D2B', accent: '#D4A853', background: '#F8F5F2', dark: '#2C1E16', success: '#2E7D32', warning: '#D97706', danger: '#C62828' },
            fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], serif: ['Cormorant Garamond', 'serif'] },
            backgroundImage: { 'coffee-pattern': "url('https://www.transparenttextures.com/patterns/cubes.png')" }
        }}}
    </script>
    <style>
        body { background-color: #F8F5F2; background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png'); }
        .cjm-step { position: relative; flex: 1; text-align: center; padding: 1rem 0.5rem; }
        .cjm-step:not(:last-child)::after { content: ''; position: absolute; top: 50%; right: -10%; width: 20%; height: 2px; background: #D4A853; opacity: 0.3; }
        .active-step { background: #5A3D2B; color: white; border-radius: 1rem; }
        .loyalty-badge { background: linear-gradient(135deg, #E2B96B, #B8860B); color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 10px; font-weight: bold; text-transform: uppercase; box-shadow: 0 4px 6px -1px rgba(184, 134, 11, 0.3); }
        .card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 1.25rem; border: 1px solid rgba(212, 168, 83, 0.15); box-shadow: 0 10px 25px -5px rgba(90, 61, 43, 0.05), 0 8px 10px -6px rgba(90, 61, 43, 0.01); }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:.5rem 1.25rem; gap: 0.5rem; border-radius:1rem; background:linear-gradient(to right, #5A3D2B, #3E2A1E); color:#fff; font-size:.875rem; font-weight:500; transition: all 0.3s; box-shadow: 0 4px 12px rgba(90, 61, 43, 0.2); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(90, 61, 43, 0.3); }
        .sidebar { background: linear-gradient(180deg, #2C1E16 0%, #1A120D 100%); position: relative; }
        .sidebar::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('https://www.transparenttextures.com/patterns/wood-pattern.png'); opacity: 0.05; pointer-events: none; }
        .nav-link { position: relative; transition: all 0.3s; }
        .nav-link::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 0; height: 0; background: #D4A853; border-radius: 0 4px 4px 0; transition: all 0.3s; }
        .nav-link:hover::before { width: 4px; height: 60%; }
        .nav-link.active { background: rgba(212, 168, 83, 0.1); color: #D4A853; }
        .nav-link.active::before { width: 4px; height: 80%; }
    </style>
    @stack('head')
</head>
<body class="text-dark font-sans h-screen overflow-hidden selection:bg-accent selection:text-white">
@php $staff = session('staff'); $isAdmin = ($staff['type'] ?? '') === 'admin'; @endphp

@if($staff)
<div class="h-screen w-full flex overflow-hidden lg:flex-row flex-col relative">
    <!-- Overlay Backdrop untuk Mobile -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-dark/60 backdrop-blur-sm z-40 hidden transition-opacity opacity-0 lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="sidebar fixed lg:static inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex w-72 shrink-0 flex-col text-white p-6 space-y-8 h-screen shadow-2xl">
        <div class="flex items-center space-x-4 px-2">
            <div class="w-13 flex items-center justify-center shadow-lg">
                <img src="{{ asset('images/logo.png')}}"/>
            </div>
            <div class="flex-1">
                <h1 class="font-serif text-2xl font-bold tracking-wide">AROMA</h1>
                <p class="text-[9px] uppercase tracking-[0.2em] text-accent font-semibold">Coffee Experience</p>
            </div>
            <!-- Tombol Close (Mobile Saja) -->
            <button onclick="toggleSidebar()" class="lg:hidden text-white/50 hover:text-white p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <nav class="flex-1 space-y-1 mt-4 overflow-y-auto pr-2 pb-4">
            <p class="text-[10px] text-white/40 uppercase tracking-widest px-4 mb-3 font-semibold">Menu Utama</p>
            @foreach(($isAdmin ? [
                ['Dashboard', route('dashboard'), 'dashboard', 'layout-dashboard'],
                ['Pelanggan', route('pelanggan.index'), 'pelanggan.*', 'users'],
                ['Produk Menu', route('produk.index'), 'produk.*', 'coffee'],
                ['Transaksi', route('transaksi.index'), 'transaksi.*', 'receipt'],
                ['Feedback', route('feedback.index'), 'feedback.*', 'message-circle-heart'],
                ['Journey Map', route('cjm.index'), 'cjm.*', 'map'],
                ['Segmentasi pelanggan', route('segmentasi.index'), 'segmentasi.*', 'brain-circuit'],
            ] : [
                ['Dashboard', route('dashboard'), 'dashboard', 'layout-dashboard'],
                ['Kasir Transaksi', route('transaksi.create'), 'transaksi.create', 'calculator'],
                ['Data Pelanggan', route('pelanggan.index'), 'pelanggan.*', 'users'],
                ['Feedback', route('feedback.create'), 'feedback.create', 'mic'],
            ]) as [$label, $url, $pattern, $icon])
            <a href="{{ $url }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl block text-sm font-medium {{ request()->routeIs($pattern) ? 'active' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ request()->routeIs($pattern) ? 'text-accent' : 'opacity-70' }}"></i>
                {{ $label }}
            </a>
            @endforeach
        </nav>
        <div class="pt-6 border-t border-white/10 mt-auto flex items-center gap-3 px-2">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                <i data-lucide="user" class="w-5 h-5 text-accent"></i>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-semibold truncate">{{ $staff['nama'] }}</p>
                <p class="text-[10px] text-white/40 capitalize">{{ $staff['type'] }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-white/40 hover:text-danger p-2 bg-white/5 rounded-lg hover:bg-danger/10 transition-colors" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Header Mobile (Hanya muncul di layar HP) -->
        <header class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-orange-100 z-30 shadow-sm relative">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent to-yellow-600 flex items-center justify-center">
                    <i data-lucide="coffee" class="text-white w-4 h-4"></i>
                </div>
                <h1 class="font-serif text-xl font-bold tracking-wide text-dark">AROMA</h1>
            </div>
            <button onclick="toggleSidebar()" class="p-2 border border-orange-200 rounded-lg text-primary hover:bg-orange-50 transition-colors focus:outline-none focus:ring-2 focus:ring-accent">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </header>

        <div class="flex-1 p-4 md:p-10 overflow-y-auto relative">
            <!-- Dekorasi Background Main Content -->
        <div class="absolute top-0 right-0 p-32 bg-accent/5 rounded-bl-full pointer-events-none -z-10"></div>
        <div class="absolute bottom-0 left-0 p-40 bg-primary/5 rounded-tr-full pointer-events-none -z-10"></div>
        
        <div class="max-w-7xl mx-auto">
            @include('partials.alerts')
            @yield('content')
        </div>
    </main>
</div>
@else @yield('content') @endif

<script>
    // Inisialisasi ikon
    lucide.createIcons();
    
    // Logika buka/tutup Sidebar Mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        
        // Cek apakah sidebar sedang tersembunyi
        const isClosed = sidebar.classList.contains('-translate-x-full');
        
        if (isClosed) {
            // Buka Sidebar
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            // Sedikit delay agar transisi opacity terlihat
            setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
        } else {
            // Tutup Sidebar
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0');
            // Tunggu transisi opacity selesai baru sembunyikan elemennya
            setTimeout(() => backdrop.classList.add('hidden'), 300);
        }
    }
</script>
@stack('scripts')
</body>
</html>
