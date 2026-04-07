@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
    <h2 class="text-2xl font-bold text-white mb-6">Sign In</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-form-input name="email" label="Email" type="email" placeholder="you@example.com" :required="true" />
        <x-form-input name="password" label="Password" type="password" placeholder="Enter your password" :required="true" />

        <div class="mt-6">
            <x-button type="submit" class="w-full">Sign In</x-button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-[#3b82f6] hover:text-blue-400 transition-colors">Register</a>
    </p>
@endsection
