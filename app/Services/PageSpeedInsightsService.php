<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PageSpeedInsightsService
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $key,
        private readonly int $connectTimeout,
        private readonly int $timeout,
        private readonly bool $verify = true,
    ) {}

    /**
     * @return array{url: string, strategy: string, score: int, metrics: array{fcpMs: int|null, lcpMs: int|null, cls: float|null, tbtMs: int|null, speedIndexMs: int|null}}
     *
     * @throws RequestException
     */
    public function fetchPerformance(string $url, string $strategy = 'mobile'): array
    {
        $query = [
            'url' => $url,
            'strategy' => $strategy,
            'category' => 'performance',
            'key' => $this->key,
        ];

        $request = Http::connectTimeout((int) $this->connectTimeout)
            ->timeout((int) $this->timeout)
            ->acceptJson();

        if (! $this->verify) {
            $request = $request->withOptions(['verify' => false]);
        }

        $response = $request->get($this->baseUrl, $query)->throw();

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('PageSpeed Insights response was not valid JSON.');
        }

        $score = $this->performanceScoreFromPayload($payload);

        return [
            'url' => $url,
            'strategy' => $strategy,
            'score' => $score,
            'metrics' => [
                'fcpMs' => $this->metricMs($payload, 'first-contentful-paint'),
                'lcpMs' => $this->metricMs($payload, 'largest-contentful-paint'),
                'cls' => $this->metricFloat($payload, 'cumulative-layout-shift'),
                'tbtMs' => $this->metricMs($payload, 'total-blocking-time'),
                'speedIndexMs' => $this->metricMs($payload, 'speed-index'),
            ],
        ];
    }

    /**
     * @param  list<string>  $strategies
     * @return array<string, array{url: string, strategy: string, score: int, metrics: array{fcpMs: int|null, lcpMs: int|null, cls: float|null, tbtMs: int|null, speedIndexMs: int|null}}>
     *
     * @throws RequestException
     */
    public function fetchPerformanceForStrategies(string $url, array $strategies = ['mobile', 'desktop']): array
    {
        $results = [];

        foreach ($strategies as $strategy) {
            $results[$strategy] = $this->fetchPerformance($url, $strategy);
        }

        return $results;
    }

    /**
     * @param  array<mixed>  $payload
     */
    private function performanceScoreFromPayload(array $payload): int
    {
        $score = data_get($payload, 'lighthouseResult.categories.performance.score');

        if (! is_numeric($score)) {
            throw new RuntimeException('PageSpeed Insights response did not include a performance score.');
        }

        $percent = (int) round(((float) $score) * 100);

        return max(0, min(100, $percent));
    }

    /**
     * @param  array<mixed>  $payload
     */
    private function metricMs(array $payload, string $auditKey): ?int
    {
        $value = data_get($payload, "lighthouseResult.audits.{$auditKey}.numericValue");

        if (! is_numeric($value)) {
            return null;
        }

        return max(0, (int) round((float) $value));
    }

    /**
     * @param  array<mixed>  $payload
     */
    private function metricFloat(array $payload, string $auditKey): ?float
    {
        $value = data_get($payload, "lighthouseResult.audits.{$auditKey}.numericValue");

        if (! is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }
}
