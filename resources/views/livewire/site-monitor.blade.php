<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 md:p-6">
    @include('partials.site-monitor.header')

    @include('partials.site-monitor.stats', ['stats' => $this->stats])

    @include('partials.site-monitor.add-form')

    <div class="flex flex-col gap-4">
        @foreach ($this->sites as $site)
            <div wire:key="site-{{ $site['id'] }}">
                @include('partials.site-monitor.site-card', ['site' => $site])
            </div>
        @endforeach

        @if (count($this->sites) === 0)
            <div class="rounded-xl border border-zinc-700 bg-zinc-800/50 px-6 py-12 text-center dark:border-zinc-600">
                <flux:text variant="subtle">{{ __('No websites monitored yet. Add one above.') }}</flux:text>
            </div>
        @endif
    </div>
</div>
