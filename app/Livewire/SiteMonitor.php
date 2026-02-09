<?php

namespace App\Livewire;

use App\Models\Site;
use Livewire\Component;

class SiteMonitor extends Component
{
    public string $newName = '';

    public string $newUrl = '';

    public array $sites = [];

    public function mount(): void
    {
        $this->loadSites();
    }

    /**
     * @return array<int, array{id: string, name: string, url: string, status: string, responseTime: int, lastChecked: string, uptime: string}>
     */
    private function loadSites(): void
    {
        $this->sites = Site::query()
            ->orderBy('name')
            ->get()
            ->map(fn (Site $site): array => [
                'id' => (string) $site->id,
                'name' => $site->name,
                'url' => $site->url,
                'status' => $site->status ?? 'operational',
                'responseTime' => $site->response_time ?? 0,
                'lastChecked' => '—',
                'uptime' => '—',
            ])
            ->values()
            ->all();
    }

    public function addSite(): void
    {
        $this->validate([
            'newName' => ['required', 'string', 'max:255'],
            'newUrl' => ['required', 'string', 'url'],
        ], [], [
            'newName' => __('name'),
            'newUrl' => __('website URL'),
        ]);

        Site::query()->create([
            'name' => $this->newName,
            'url' => $this->newUrl,
        ]);

        $this->newName = '';
        $this->newUrl = '';
        $this->resetValidation();
        $this->loadSites();
    }

    public function refreshAll(): void
    {
        foreach ($this->sites as $i => $site) {
            $this->sites[$i]['responseTime'] = random_int(60, 350);
            $this->sites[$i]['lastChecked'] = 'less than a minute ago';
        }
    }

    public function removeSite(string $id): void
    {
        Site::query()->find($id)?->delete();
        $this->loadSites();
    }

    /**
     * @return array{total: int, operational: int, down: int, degraded: int, avgResponse: int}
     */
    public function getStatsProperty(): array
    {
        $total = count($this->sites);
        $operational = count(array_filter($this->sites, fn (array $s) => $s['status'] === 'operational'));
        $down = count(array_filter($this->sites, fn (array $s) => $s['status'] === 'down'));
        $degraded = count(array_filter($this->sites, fn (array $s) => $s['status'] === 'degraded'));
        $avgResponse = $total > 0
            ? (int) round(array_sum(array_column($this->sites, 'responseTime')) / $total)
            : 0;

        return [
            'total' => $total,
            'operational' => $operational,
            'down' => $down,
            'degraded' => $degraded,
            'avgResponse' => $avgResponse,
        ];
    }

    public function render()
    {
        return view('livewire.site-monitor');
    }
}
