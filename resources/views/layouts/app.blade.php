<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        <div class="mb-4 hidden in-data-flux-sidebar-collapsed-desktop:lg:flex">
            <flux:sidebar.toggle icon="bars-2" inset="left" />
        </div>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
