@extends('layouts.app')
@section('title', 'Feedback')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Data Feedback</h1>
        <a href="{{ route('feedback.create') }}" class="btn">+ Catat Feedback</a>
    </div>
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr>
                <th class="px-6 py-3 text-left">Tanggal</th><th class="px-6 py-3 text-left">Pelanggan</th>
                <th class="px-6 py-3 text-left">Rating</th><th class="px-6 py-3 text-left">Kategori</th>
                <th class="px-6 py-3 text-left">Komentar</th><th class="px-6 py-3 text-left">Status</th>
                @if(session('staff.type')==='admin')<th class="px-6 py-3">Aksi</th>@endif
            </tr></thead>
            <tbody>
            @forelse($feedbacks as $f)
            <tr class="border-b">
                <td class="px-6 py-4">{{ $f->tanggal_feedback->format('d M Y') }}</td>
                <td class="px-6 py-4">{{ $f->pelanggan->nama ?? '-' }}</td>
                <td class="px-6 py-4 font-bold">{{ $f->rating }}/5</td>
                <td class="px-6 py-4">{{ $f->kategori }}<br><span class="text-xs text-gray-400">{{ $f->tahap_label }}</span></td>
                <td class="px-6 py-4 max-w-xs truncate">{{ $f->komentar }}</td>
                <td class="px-6 py-4">
                    @if($f->isPainPoint())<span class="px-2 py-1 text-xs bg-danger/10 text-danger rounded-full">Pain Point</span>
                    @else<span class="px-2 py-1 text-xs bg-success/10 text-success rounded-full">Aman</span>@endif
                </td>
                @if(session('staff.type')==='admin')
                <td class="px-6 py-4">
                    <form action="{{ route('feedback.destroy', $f) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-danger">Hapus</button></form>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada feedback</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $feedbacks->links() }}</div>
    </div>
</div>
@endsection
