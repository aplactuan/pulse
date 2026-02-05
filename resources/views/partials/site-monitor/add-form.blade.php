<div class="flex flex-col gap-3 sm:flex-row sm:items-center">
    <flux:field class="min-w-0 flex-1">
        <flux:input
            wire:model="newUrl"
            wire:keydown.enter="addSite"
            type="url"
            :placeholder="__('Enter website URL (e.g., example.com)')"
            class="w-full"
        />
        <flux:error name="newUrl" />
    </flux:field>
    <flux:button
        variant="primary"
        icon="plus"
        wire:click="addSite"
        wire:loading.attr="disabled"
        class="bg-emerald-600 hover:bg-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-500"
    >
        <flux:icon.plus class="size-4" />
        {{ __('Add Website') }}
    </flux:button>
</div>
