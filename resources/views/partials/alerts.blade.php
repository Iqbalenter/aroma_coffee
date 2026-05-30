@if(session('success'))
    <div class="mb-5 flex items-start gap-3 rounded-3xl border border-success/15 bg-success/10 px-5 py-4 text-sm font-semibold text-success">
        <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="mb-5 flex items-start gap-3 rounded-3xl border border-danger/15 bg-danger/10 px-5 py-4 text-sm font-semibold text-danger">
        <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-3xl border border-danger/15 bg-danger/10 px-5 py-4 text-sm font-semibold text-danger">
        <div class="mb-2 flex items-center gap-2">
            <i data-lucide="alert-triangle" class="h-5 w-5"></i>
            <span>Periksa kembali input berikut:</span>
        </div>

        <ul class="list-disc space-y-1 pl-7">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
