@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-2xl">
    <x-card title="Profile Settings" subtitle="Update your account information">
        <form method="POST" action="{{ route('settings.profile.update') }}">
            @csrf
            @method('PUT')

            <x-form-input name="name" label="Name" :value="old('name', $user->name ?? '')" :required="true" />
            <x-form-input name="email" label="Email" type="email" :value="old('email', $user->email ?? '')" :required="true" />

            <div class="mt-8 pt-6 border-t border-[#2a2d3a]">
                <h4 class="text-lg font-semibold text-white mb-4">Change Password</h4>
                <p class="text-sm text-gray-500 mb-4">Leave blank to keep your current password.</p>

                <x-form-input name="current_password" label="Current Password" type="password" placeholder="Enter current password" />
                <x-form-input name="password" label="New Password" type="password" placeholder="Enter new password" />
                <x-form-input name="password_confirmation" label="Confirm New Password" type="password" placeholder="Confirm new password" />
            </div>

            <div class="mt-6">
                <x-button type="submit">Save Profile</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
