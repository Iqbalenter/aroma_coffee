@extends('layouts.app')
@section('title', 'Kualitatif Customer Journey')
@section('content')
<div class="space-y-6">
    <header class="mb-8">
        <h1 class="text-3xl font-bold">Kualitatif Customer Journey Map</h1>
        <p class="text-gray-500">Visualisasi pengalaman pelanggan berbasis Thematic & Sentiment Analysis pada Voice of Customer.</p>
    </header>

    <!-- Emotional Curve Chart Placeholder -->
    <div class="card p-6 mb-8 bg-gradient-to-br from-amber-50 to-orange-50 border-orange-200">
        <h3 class="font-serif text-2xl text-primary mb-4">Emotional Curve (Kurva Emosi)</h3>
        <p class="text-sm text-gray-500 mb-6">Membaca sentimen kualitatif pelanggan dari awal menyadari Aroma Coffee hingga menjadi pelanggan loyal.</p>
        
        <div class="flex flex-wrap gap-2 items-end justify-between min-h-[150px] relative">
            <!-- Connecting Line -->
            <div class="absolute w-full h-1 bg-orange-200 top-1/2 -z-10 translate-y-4"></div>
            
            @foreach($stageData as $key => $data)
                @php
                    $sentimen_dominan = $data['journey']->sentimen_dominan ?? 'netral';
                    $tinggi = 'translate-y-0'; // Netral
                    $emoji = '😐';
                    $warna = 'text-gray-500';
                    
                    if($sentimen_dominan == 'positif') {
                        $tinggi = '-translate-y-8';
                        $emoji = '😍';
                        $warna = 'text-green-500';
                    } elseif($sentimen_dominan == 'negatif') {
                        $tinggi = 'translate-y-8';
                        $emoji = '😡';
                        $warna = 'text-red-500';
                    }
                @endphp
            <div class="cjm-step flex-1 text-center bg-white p-3 rounded-xl shadow-sm border border-orange-100 transition-transform {{ $tinggi }} relative">
                <p class="text-[10px] font-bold uppercase text-orange-800 mb-1 z-10">{{ $data['meta']['title'] }}</p>
                <div class="text-4xl my-2 mx-auto">{{ $emoji }}</div>
                <p class="text-[10px] font-bold {{ $warna }} uppercase">{{ $sentimen_dominan }}</p>
                <p class="text-[9px] mt-1 text-gray-500">{{ $data['count'] }} Suara</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Thematic & Verbatim Box -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($stageData as $key => $data)
        <div class="card p-5 border-t-4 shadow-sm {{ $data['pain_points'] > 0 ? 'border-red-400 bg-red-50/20' : 'border-green-400' }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="font-bold text-gray-800">{{ $data['meta']['title'] }}</h3>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $data['count'] }} data</span>
            </div>
            
            <div class="space-y-4">
                <!-- Temuan Tema -->
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Tema Utama (Thematic Coding)</p>
                    <div class="flex flex-wrap gap-1">
                        @forelse($data['top_themes'] as $tema => $jumlah)
                            <span class="text-[10px] bg-indigo-50 text-indigo-700 border border-indigo-100 px-2 py-0.5 rounded-full">{{ $tema }} ({{ $jumlah }})</span>
                        @empty
                            <span class="text-[10px] text-gray-400 italic">Belum ada tema</span>
                        @endforelse
                    </div>
                </div>

                <!-- Pain Points Warning -->
                @if($data['pain_points'] > 0)
                <div class="text-xs text-red-700 bg-red-100 border border-red-200 p-2 rounded flex items-center gap-2">
                    <span>⚠</span> Ada {{ $data['pain_points'] }} keluhan (sentimen negatif)
                </div>
                @endif
                
                <!-- Verbatim Comments (Voice of Customer) -->
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Voice of Customer (Verbatim)</p>
                    <div class="space-y-2">
                        @forelse($data['recent_comments'] as $feedback)
                            @php
                                $badgeColor = $feedback->sentimen == 'positif' ? 'bg-green-100 text-green-700' : ($feedback->sentimen == 'negatif' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700');
                            @endphp
                            <div class="text-xs italic bg-white p-2 rounded border border-gray-100 relative group">
                                <span class="absolute top-1 right-2 text-[8px] {{ $badgeColor }} px-1 rounded">{{ $feedback->sentimen }}</span>
                                <span class="text-gray-400">"</span>{{ Str::limit($feedback->komentar, 70) }}<span class="text-gray-400">"</span>
                            </div>
                        @empty
                            <p class="text-xs italic text-gray-400">Belum ada suara pelanggan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
