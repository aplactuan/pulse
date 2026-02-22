@props(['site'])

<div class="rounded-xl border border-zinc-700 bg-zinc-800/80 p-4 dark:border-zinc-600">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
                <flux:heading size="lg" class="text-white">{{ $site['name'] }}</flux:heading>
                <a href="{{ $site['url'] }}" target="_blank" rel="noopener noreferrer" class="text-zinc-400 hover:text-zinc-300" aria-label="{{ __('Open in new tab') }}">
                    <flux:icon.arrow-top-right-on-square class="size-4" />
                </a>
            </div>
            <flux:text variant="subtle" class="mt-0.5 truncate text-sm">{{ $site['url'] }}</flux:text>
            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1">
                <span class="inline-flex items-center gap-1.5">
                    <span
                        class="size-2 rounded-full {{ match ($site['status'] ?? 'operational') { 'degraded' => 'bg-amber-500', 'down' => 'bg-red-500', default => 'bg-emerald-900' } }}"
                        aria-hidden="true"
                    ></span>
                    <flux:badge :color="match ($site['status'] ?? 'operational') { 'degraded' => 'amber', 'down' => 'red', default => 'green' }" variant="outline" size="sm">
                        {{ match ($site['status'] ?? 'operational') { 'degraded' => __('Degraded'), 'down' => __('Down'), default => __('Operational') } }}{{ ! empty($site['statusCode']) ? ' (' . $site['statusCode'] . ')' : '' }}
                    </flux:badge>
                </span>
                <span class="inline-flex items-center gap-1 text-sm text-zinc-400">
                    <flux:icon.bolt class="size-4" />
                    {{ $site['responseTime'] }}ms
                </span>
                <span class="inline-flex items-center gap-1 text-sm text-zinc-400">
                    <flux:icon.clock class="size-4" />
                    {{ $site['lastChecked'] }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-4 sm:flex-col sm:items-end">
            <flux:button
                :wire:click="'removeSite(' . \Illuminate\Support\Js::from($site['id']) . ')'"
                wire:confirm="{{ __('Are you sure you want to remove this site from monitoring?') }}"
                class="text-zinc-400 hover:text-red-400"
                aria-label="{{ __('Remove site') }}"
                variant="subtle"
            >
                <span class="text-zinc-400 hover:text-red-400">
                    <flux:icon.trash class="size-4" />
                </span>
                
            </flux:button>
            <div class="text-end">
                <flux:text variant="subtle" class="text-xs">{{ __('Uptime') }}</flux:text>
                <flux:heading size="sm" class="text-emerald-400">{{ $site['uptime'] }}</flux:heading>
            </div>
            <div class="text-end">
                <flux:text variant="subtle" class="text-xs">{{ __('Pagespeed') }}</flux:text>
                <div class="mt-0.5 inline-flex flex-wrap items-center justify-end gap-x-3 gap-y-1 text-sm">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="text-zinc-400">{{ __('Mobile') }}</span>
                        <span class="font-semibold {{ match (true) { ($site['pagespeedMobileScore'] ?? null) === null => 'text-zinc-500', ($site['pagespeedMobileScore'] ?? null) < 60 => 'text-red-400', ($site['pagespeedMobileScore'] ?? null) < 85 => 'text-amber-400', default => 'text-emerald-400' } }}">
                            {{ ($site['pagespeedMobileScore'] ?? null) !== null ? $site['pagespeedMobileScore'] : '—' }}
                        </span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="text-zinc-400">{{ __('Desktop') }}</span>
                        <span class="font-semibold {{ match (true) { ($site['pagespeedDesktopScore'] ?? null) === null => 'text-zinc-500', ($site['pagespeedDesktopScore'] ?? null) < 60 => 'text-red-400', ($site['pagespeedDesktopScore'] ?? null) < 85 => 'text-amber-400', default => 'text-emerald-400' } }}">
                            {{ ($site['pagespeedDesktopScore'] ?? null) !== null ? $site['pagespeedDesktopScore'] : '—' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
