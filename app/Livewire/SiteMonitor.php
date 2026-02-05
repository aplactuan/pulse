<?php

namespace App\Livewire;

use Livewire\Component;

class SiteMonitor extends Component
{
    public string $newUrl = '';

    public array $sites = [];

    public function mount(): void
    {
        $this->sites = $this->defaultSites();
    }

    /**
     * @return array<int, array{id: string, name: string, url: string, status: string, responseTime: int, lastChecked: string, uptime: string}>
     */
    private function defaultSites(): array
    {
        return [
            [
                'id' => '1',
                'name' => 'Google',
                'url' => 'https://google.com',
                'status' => 'operational',
                'responseTime' => 228,
                'lastChecked' => 'less than a minute ago',
                'uptime' => '99.9%',
            ],
            [
                'id' => '2',
                'name' => 'GitHub',
                'url' => 'https://github.com',
                'status' => 'operational',
                'responseTime' => 97,
                'lastChecked' => 'less than a minute ago',
                'uptime' => '99.8%',
            ],
            [
                'id' => '3',
                'name' => 'Twitter',
                'url' => 'https://twitter.com',
                'status' => 'degraded',
                'responseTime' => 320,
                'lastChecked' => 'less than a minute ago',
                'uptime' => '99.4%',
            ],
        ];
    }

    public function addSite(): void
    {
        $this->validate([
            'newUrl' => ['required', 'string', 'url'],
        ], [], ['newUrl' => __('website URL')]);

        $url = parse_url($this->newUrl, PHP_URL_HOST) ?? $this->newUrl;
        $name = ucfirst(str_replace('.com', '', (string) $url));

        $this->sites[] = [
            'id' => uniqid('', true),
            'name' => $name,
            'url' => $this->newUrl,
            'status' => 'operational',
            'responseTime' => random_int(80, 400),
            'lastChecked' => 'less than a minute ago',
            'uptime' => '99.'.random_int(0, 9).'%',
        ];

        $this->newUrl = '';
        $this->resetValidation();
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
        $this->sites = array_values(array_filter($this->sites, fn (array $s) => $s['id'] !== $id));
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
