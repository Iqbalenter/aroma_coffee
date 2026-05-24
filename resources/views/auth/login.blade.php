@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="card w-full max-w-md p-8">
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mb-4 text-white text-2xl">
                <img src="{{ asset('images/logo.png')}}"/>
            </div>
            <h1 class="text-2xl font-bold">Aroma Coffee Bland</h1>
            <p class="text-gray-500 text-sm">Customer Journey Mapping System</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="input" placeholder="admin" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="input" placeholder="password" required>
            </div>
            <button type="submit" class="btn w-full">Login</button>
        </form>
        <div class="mt-6 text-sm text-center text-gray-500 space-y-1">
            <p>Demo: <strong>admin</strong> / password (Admin)</p>
            <p>Demo: <strong>operator</strong> / password (Operator)</p>
        </div>
    </div>
</div>
@endsection
