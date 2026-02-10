<div class="flex flex-col gap-3 sm:flex-row sm:items-end">
    <flux:field class="min-w-0 flex-1">
        <flux:label class="text-emerald-400">{{ __('Name') }}</flux:label>
        <flux:input
            wire:model="newName"
            wire:keydown.enter="addSite"
            type="text"
            :placeholder="__('Website name')"
            class="w-full"
        />
        <flux:error name="newName" />
    </flux:field>
    <flux:field class="min-w-0 flex-1">
        <flux:label class="text-emerald-400">{{ __('URL') }}</flux:label>
        <flux:input
            wire:model="newUrl"
            wire:keydown.enter="addSite"
            type="url"
            :placeholder="__('https://example.com')"
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
        {{ __('Add Website') }}
    </flux:button>
</div>
