<x-layouts.guest-shell title="Confirm it is you">
    <p class="mb-3 text-sm text-muted">This action changes what the account may do. Confirm with your password and a fresh code; it lasts 10 minutes.</p>
    <form method="POST" action="{{ route('reauth') }}">
        @csrf
        <x-field label="Password" name="password" type="password" autocomplete="current-password" required autofocus />
        <x-field label="Code from your authenticator" name="code" inputmode="numeric" autocomplete="one-time-code" required />
        <x-button class="w-full">Confirm</x-button>
    </form>
</x-layouts.guest-shell>
