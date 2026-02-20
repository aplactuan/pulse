<?php

namespace App\Livewire;

use App\Models\Site;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Throwable;

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
     * @return array<int, array{id: string, name: string, url: string, status: string, statusCode: int|null, responseTime: int, lastChecked: string, uptime: string, pagespeedDesktopScore: int|null, pagespeedMobileScore: int|null}>
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
                'statusCode' => $site->status_code,
                'responseTime' => $site->response_time ?? 0,
                'lastChecked' => $site->last_checked_at?->diffForHumans() ?? '—',
                'uptime' => '—',
                'pagespeedDesktopScore' => $site->pagespeed_desktop_score,
                'pagespeedMobileScore' => $site->pagespeed_mobile_score,
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
        $sites = Site::query()->get();

        foreach ($sites as $site) {
            $start = hrtime(true);

            try {
                $response = Http::connectTimeout(5)
                    ->timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Pulse Site Monitor',
                        'Accept' => '*/*',
                    ])
                    ->get($site->url);

                $elapsedMs = (int) round((hrtime(true) - $start) / 1_000_000);
                $statusCode = $response->status();

                $site->forceFill([
                    'status_code' => $statusCode,
                    'response_time' => max(0, $elapsedMs),
                    'status' => $this->statusFromStatusCode($statusCode),
                    'last_checked_at' => now(),
                ])->save();
            } catch (Throwable) {
                $elapsedMs = (int) round((hrtime(true) - $start) / 1_000_000);

                $site->forceFill([
                    'status_code' => null,
                    'response_time' => max(0, $elapsedMs),
                    'status' => 'down',
                    'last_checked_at' => now(),
                ])->save();
            }
        }

        $this->loadSites();
    }

    private function statusFromStatusCode(int $statusCode): string
    {
        if ($statusCode < 400) {
            return 'operational';
        }

        if ($statusCode < 500) {
            return 'degraded';
        }

        return 'down';
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
