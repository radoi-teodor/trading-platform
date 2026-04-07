@extends('layouts.auth')

@section('title', 'Create Account')

@section('content')
    <h2 class="text-2xl font-bold text-white mb-6">Create Account</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-form-input name="name" label="Name" placeholder="Your name" :required="true" />
        <x-form-input name="email" label="Email" type="email" placeholder="you@example.com" :required="true" />
        <x-form-input name="password" label="Password" type="password" placeholder="Choose a password" :required="true" />
        <x-form-input name="password_confirmation" label="Confirm Password" type="password" placeholder="Confirm your password" :required="true" />

        <div class="mt-6">
            <x-button type="submit" class="w-full">Create Account</x-button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="text-[#3b82f6] hover:text-blue-400 transition-colors">Login</a>
    </p>
@endsection
