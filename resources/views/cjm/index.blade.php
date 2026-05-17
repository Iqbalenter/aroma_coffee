@extends('layouts.app')
@section('title', 'Customer Journey')
@section('content')
<div class="space-y-6">
    <header class="mb-8">
        <h1 class="text-3xl font-bold">Journey Mapping</h1>
        <p class="text-gray-500">Visualisasi perjalanan pelanggan dan identifikasi masalah pada setiap tahap.</p>
    </header>
    <div class="card p-6 mb-8">
        <h3 class="font-serif text-2xl text-primary mb-6">Customer Journey Pipeline</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($stageData as $key => $data)
            <div class="cjm-step flex-1 min-w-[100px] {{ $data['pain_points'] > 0 ? 'active-step' : '' }}">
                <p class="text-[10px] font-bold uppercase mb-1">{{ $data['meta']['title'] }}</p>
                <p class="text-2xl font-bold">{{ $data['count'] }}</p>
                <p class="text-[9px] italic">{{ $data['meta']['desc'] }}</p>
                @if($data['journey'])<p class="text-xs mt-1">⭐ {{ $data['journey']->rata_rating }}</p>@endif
            </div>
            @endforeach
        </div>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($stageData as $key => $data)
        <div class="card p-5 space-y-3 border-t-4 {{ $data['pain_points'] > 0 ? 'border-danger' : 'border-primary' }}">
            <h3 class="font-bold">{{ $data['meta']['title'] }}</h3>
            <p class="text-xs text-gray-400">Total: {{ $data['count'] }} feedback</p>
            @if($data['pain_points'] > 0)
            <p class="text-xs text-danger bg-danger/5 p-2 rounded">⚠ {{ $data['pain_points'] }} pain point</p>
            @endif
            @foreach($data['recent_comments'] as $comment)
            <p class="text-xs italic bg-background p-2 rounded border">"{{ $comment }}"</p>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@endsection
