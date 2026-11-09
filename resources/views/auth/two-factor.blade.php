<x-layouts.guest-shell title="Authenticator code">
    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf
        <x-field label="Code from your authenticator" name="code" inputmode="numeric" autocomplete="one-time-code" required autofocus />
        <x-button class="w-full">Continue</x-button>
    </form>
</x-layouts.guest-shell>
