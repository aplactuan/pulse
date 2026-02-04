<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#111827] antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-sm rounded-xl border border-white/10 bg-[#1f2937] p-8 shadow-xl">
                <a href="{{ route('home') }}" class="mb-6 flex flex-col items-center gap-3 font-medium" wire:navigate>
                    <span class="flex size-10 items-center justify-center rounded-lg text-[#10b981]">
                        <x-pulse-logo-icon class="size-10" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6 text-white [--color-accent:#10b981] [--color-accent-foreground:#fff]">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
