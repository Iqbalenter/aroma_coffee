@extends('layouts.app')
@section('title', 'Catat Feedback')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold">Catat Feedback Pelanggan</h1>
    <div class="card p-6">
        <form method="POST" action="{{ route('feedback.store') }}" class="space-y-4">@csrf
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-sm block mb-1">Pelanggan</label>
                    <select name="id_pelanggan" class="input" required>
                        <option value="">-- Pilih --</option>
                        @foreach($pelanggan as $c)<option value="{{ $c->id_pelanggan }}">{{ $c->nama }}</option>@endforeach
                    </select>
                </div>
                <div><label class="text-sm block mb-1">Produk</label>
                    <select name="id_produk" class="input" required>
                        @foreach($produk as $p)<option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-sm block mb-1">Kategori</label>
                    <select name="kategori" class="input" required>
                        <option>Rasa Produk</option><option>Pelayanan</option><option>Harga</option><option>Tempat/Suasana</option>
                    </select>
                </div>
                <div><label class="text-sm block mb-1">Tahap Journey</label>
                    <select name="tahap_journey" class="input" required>
                        @foreach(\App\Models\Feedback::TAHAPAN as $k=>$v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label class="text-sm block mb-1">Rating (1-5)</label>
                <select name="rating" class="input" required>@for($i=1;$i<=5;$i++)<option value="{{ $i }}" @selected($i==5)>{{ $i }}</option>@endfor</select>
            </div>
            <div><label class="text-sm block mb-1">Komentar</label><textarea name="komentar" rows="4" class="input" required></textarea></div>
            <button type="submit" class="btn w-full">Simpan Feedback</button>
        </form>
    </div>
</div>
@endsection
