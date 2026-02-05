<div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3">
        <div class="flex size-10 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-400">
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 3v18h18"/>
                <path d="m19 9-5 5-4-4-3 3"/>
            </svg>
        </div>
        <div>
            <flux:heading size="xl">{{ __('Site Monitor') }}</flux:heading>
            <flux:subheading class="text-zinc-400">{{ __('Real-time website monitoring') }}</flux:subheading>
        </div>
    </div>
    <flux:button
        variant="ghost"
        icon="arrow-path"
        wire:click="refreshAll"
        wire:loading.attr="disabled"
        class="mt-2 sm:mt-0"
    >
        {{ __('Refresh All') }}
    </flux:button>
</div>
