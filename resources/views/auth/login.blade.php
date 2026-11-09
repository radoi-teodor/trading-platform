<x-layouts.guest-shell title="Log in">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <x-field label="Email" name="email" type="email" autocomplete="username" value="{{ old('email') }}" required autofocus />
        <x-field label="Password" name="password" type="password" autocomplete="current-password" required />
        <x-button class="w-full">Log in</x-button>
    </form>
</x-layouts.guest-shell>
