<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aroma Coffee Bland') - CJM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: {
            colors: { primary: '#6F4E37', accent: '#D4A853', background: '#FDF6EC', dark: '#1E1209', success: '#4CAF50', warning: '#FF9800', danger: '#F44336' },
            fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Cormorant Garamond', 'serif'] }
        }}}
    </script>
    <style>
        .cjm-step { position: relative; flex: 1; text-align: center; padding: 1rem 0.5rem; }
        .cjm-step:not(:last-child)::after { content: ''; position: absolute; top: 50%; right: -10%; width: 20%; height: 2px; background: #D4A853; opacity: 0.3; }
        .active-step { background: #6F4E37; color: white; border-radius: 1rem; }
        .loyalty-badge { background: linear-gradient(135deg, #D4A853, #B8860B); color: white; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .card { background:#fff; border-radius:1rem; border:1px solid rgba(111,78,55,.1); box-shadow:0 1px 3px rgba(0,0,0,.06); }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:.5rem 1rem; border-radius:.75rem; background:#6F4E37; color:#fff; font-size:.875rem; font-weight:500; }
        .btn:hover { opacity:.9; }
        .btn-outline { display:inline-flex; padding:.5rem 1rem; border-radius:.75rem; border:1px solid rgba(111,78,55,.3); color:#6F4E37; font-size:.875rem; }
        .input { width:100%; height:2.5rem; border-radius:.75rem; border:1px solid rgba(30,18,9,.2); padding:0 .75rem; font-size:.875rem; }
    </style>
    @stack('head')
</head>
<body class="bg-background text-dark font-sans min-h-screen">
@php $staff = session('staff'); $isAdmin = ($staff['type'] ?? '') === 'admin'; @endphp

@if($staff)
<div class="min-h-screen flex overflow-hidden">
    <aside class="hidden md:flex w-64 flex-col bg-dark text-white p-6 space-y-8 shrink-0">
        <div class="flex items-center space-x-3 px-2">
            <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center"><span class="font-serif font-bold text-xl">A</span></div>
            <div><h1 class="font-serif text-xl font-semibold">Aroma Coffee</h1><p class="text-[10px] uppercase tracking-widest text-accent opacity-80">Bland & Roastery</p></div>
        </div>
        <nav class="flex-1 space-y-2">
            @foreach(($isAdmin ? [
                ['Dashboard', route('dashboard'), 'dashboard'],
                ['Pelanggan', route('pelanggan.index'), 'pelanggan.*'],
                ['Produk', route('produk.index'), 'produk.*'],
                ['Transaksi', route('transaksi.index'), 'transaksi.*'],
                ['Feedback', route('feedback.index'), 'feedback.*'],
                ['Journey Mapping', route('cjm.index'), 'cjm.*'],
                ['Segmentasi', route('segmentasi.index'), 'segmentasi.*'],
                ['Laporan', route('laporan.index'), 'laporan.*'],
            ] : [
                ['Dashboard', route('dashboard'), 'dashboard'],
                ['Input Transaksi', route('transaksi.create'), 'transaksi.create'],
                ['Data Pelanggan', route('pelanggan.index'), 'pelanggan.*'],
                ['Catat Feedback', route('feedback.create'), 'feedback.create'],
            ]) as [$label, $url, $pattern])
            <a href="{{ $url }}" class="p-3 rounded-xl block text-sm font-medium transition-colors {{ request()->routeIs($pattern) ? 'bg-primary text-white' : 'hover:bg-white/5 text-white/60' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="pt-6 border-t border-white/10">
            <p class="text-xs font-semibold">{{ $staff['nama'] }}</p>
            <p class="text-[10px] text-white/40 capitalize mb-2">{{ $staff['type'] }}</p>
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="text-sm text-white/60 hover:text-danger">Keluar</button></form>
        </div>
    </aside>
    <main class="flex-1 p-4 md:p-8 overflow-y-auto">@include('partials.alerts')@yield('content')</main>
</div>
@else @yield('content') @endif
@stack('scripts')
</body>
</html>
