<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Welcome back')"
            :description="__('Sign in to your Site Monitor account')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                icon-leading="envelope"
                required
                autofocus
                autocomplete="email"
                placeholder="you@example.com"
            />

            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    icon-leading="lock-closed"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <flux:button variant="primary" type="submit" class="w-full rounded-lg bg-[#10b981] hover:bg-[#059669]" data-test="login-button">
                {{ __('Sign in') }}
            </flux:button>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-center text-sm text-zinc-400 rtl:space-x-reverse">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" class="text-[#10b981] hover:text-[#34d399]" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        @endif

        <div class="text-center">
            <flux:link :href="route('dashboard')" class="text-sm text-zinc-400 hover:text-zinc-300" wire:navigate>
                ← {{ __('Back to dashboard') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
