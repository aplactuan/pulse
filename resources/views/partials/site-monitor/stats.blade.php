@props(['stats'])

<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
    <div class="rounded-xl border border-zinc-700 bg-zinc-800/80 px-4 py-3 dark:border-zinc-600">
        <div class="flex items-center gap-2 text-zinc-400">
            <flux:icon.chart-bar class="size-4" />
            <span class="text-xs font-medium uppercase tracking-wider">{{ __('Total Sites') }}</span>
        </div>
        <flux:heading size="lg" class="mt-1 text-white">{{ $stats['total'] }}</flux:heading>
    </div>
    <div class="rounded-xl border border-emerald-500/50 bg-zinc-800/80 px-4 py-3 dark:border-emerald-500/30">
        <div class="flex items-center gap-2 text-zinc-400">
            <flux:icon.check-circle class="size-4 text-emerald-400" />
            <span class="text-xs font-medium uppercase tracking-wider">{{ __('Operational') }}</span>
        </div>
        <flux:heading size="lg" class="mt-1 text-emerald-400">{{ $stats['operational'] }}</flux:heading>
    </div>
    <div class="rounded-xl border border-red-500/50 bg-zinc-800/80 px-4 py-3 dark:border-red-500/30">
        <div class="flex items-center gap-2 text-zinc-400">
            <flux:icon.x-circle class="size-4 text-red-400" />
            <span class="text-xs font-medium uppercase tracking-wider">{{ __('Down') }}</span>
        </div>
        <flux:heading size="lg" class="mt-1 text-red-400">{{ $stats['down'] }}</flux:heading>
    </div>
    <div class="rounded-xl border border-amber-500/50 bg-zinc-800/80 px-4 py-3 dark:border-amber-500/30">
        <div class="flex items-center gap-2 text-zinc-400">
            <flux:icon.exclamation-triangle class="size-4 text-amber-400" />
            <span class="text-xs font-medium uppercase tracking-wider">{{ __('Degraded') }}</span>
        </div>
        <flux:heading size="lg" class="mt-1 text-amber-400">{{ $stats['degraded'] }}</flux:heading>
    </div>
    <div class="rounded-xl border border-zinc-700 bg-zinc-800/80 px-4 py-3 dark:border-zinc-600 max-sm:col-span-2 sm:max-lg:col-span-3 lg:col-span-1">
        <div class="flex items-center gap-2 text-zinc-400">
            <flux:icon.bolt class="size-4" />
            <span class="text-xs font-medium uppercase tracking-wider">{{ __('Avg Response') }}</span>
        </div>
        <flux:heading size="lg" class="mt-1 text-white">{{ $stats['avgResponse'] }}ms</flux:heading>
    </div>
</div>
