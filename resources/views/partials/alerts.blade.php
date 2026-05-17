@if(session('success'))
<div class="mb-4 p-3 bg-success/10 text-success rounded-xl text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-danger/10 text-danger rounded-xl text-sm">{{ session('error') }}</div>
@endif
@if($errors->any())
<div class="mb-4 p-3 bg-danger/10 text-danger rounded-xl text-sm">
    <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif
