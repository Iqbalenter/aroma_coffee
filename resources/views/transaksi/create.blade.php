@extends('layouts.app')
@section('title', 'Input Transaksi')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold">Input Transaksi Baru</h1>
    <form method="POST" action="{{ route('transaksi.store') }}" id="trxForm">@csrf
        <div class="grid md:grid-cols-2 gap-6">
            <div class="card p-6 space-y-4">
                <h2 class="font-semibold">Data Pelanggan & Pembayaran</h2>
                <div><label class="text-sm block mb-1">Pelanggan</label>
                    <select name="id_pelanggan" class="input" required>
                        <option value="">-- Pilih --</option>
                        @foreach($pelanggan as $c)<option value="{{ $c->id_pelanggan }}">{{ $c->nama }}</option>@endforeach
                    </select>
                </div>
                <div><label class="text-sm block mb-1">Metode Bayar</label>
                    <select name="metode_bayar" class="input"><option>Cash</option><option>QRIS</option><option>Transfer</option></select>
                </div>
                <div><label class="text-sm block mb-1">Catatan</label><input name="catatan" class="input" placeholder="Opsional"></div>
            </div>
            <div class="card p-6 space-y-4">
                <h2 class="font-semibold">Pilih Produk</h2>
                <div id="cartItems" class="space-y-2 max-h-64 overflow-y-auto mb-4"></div>
                @foreach($produk as $p)
                <div class="flex justify-between items-center p-3 border rounded-xl">
                    <div><p class="font-medium text-sm">{{ $p->nama_produk }}</p><p class="text-xs text-gray-500">Rp {{ number_format($p->harga,0,',','.') }}</p></div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="w-6 h-6 rounded-full bg-gray-200" onclick="changeQty({{ $p->id_produk }}, -1)">-</button>
                        <span id="qty-{{ $p->id_produk }}">0</span>
                        <button type="button" class="w-6 h-6 rounded-full bg-primary text-white" onclick="changeQty({{ $p->id_produk }}, 1)">+</button>
                    </div>
                </div>
                @endforeach
                <p class="font-bold text-right text-primary text-xl">Total: Rp <span id="totalDisplay">0</span></p>
                <button type="submit" class="btn w-full">Simpan Transaksi</button>
            </div>
        </div>
    </form>
</div>
@push('scripts')
<script>
const prices = @json($produk->pluck('harga','id_produk'));
const cart = {};
function changeQty(id, delta) {
    cart[id] = Math.max(0, (cart[id]||0) + delta);
    document.getElementById('qty-'+id).textContent = cart[id];
    renderCart();
}
function renderCart() {
    const container = document.getElementById('cartItems');
    container.innerHTML = '';
    let total = 0, idx = 0;
    for (const [id, qty] of Object.entries(cart)) {
        if (qty < 1) continue;
        const sub = prices[id] * qty; total += sub;
        container.innerHTML += `<input type="hidden" name="items[${idx}][id_produk]" value="${id}"><input type="hidden" name="items[${idx}][jumlah]" value="${qty}">`;
        idx++;
    }
    document.getElementById('totalDisplay').textContent = total.toLocaleString('id-ID');
}
document.getElementById('trxForm').addEventListener('submit', e => {
    if (!Object.values(cart).some(q => q > 0)) { e.preventDefault(); alert('Pilih minimal 1 produk'); }
});
</script>
@endpush
@endsection
