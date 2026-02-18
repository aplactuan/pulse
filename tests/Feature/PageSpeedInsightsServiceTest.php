<?php

use App\Services\PageSpeedInsightsService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

test('pagespeed insights service parses performance score and metrics', function () {
    config()->set('services.pagespeed_insights.key', 'test-key');
    config()->set('services.pagespeed_insights.base_url', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed');

    $capturedRequest = null;

    Http::fake(function (Request $request) use (&$capturedRequest) {
        $capturedRequest = $request;

        return Http::response([
            'lighthouseResult' => [
                'categories' => [
                    'performance' => [
                        'score' => 0.92,
                    ],
                ],
                'audits' => [
                    'first-contentful-paint' => ['numericValue' => 1234.0],
                    'largest-contentful-paint' => ['numericValue' => 2345.0],
                    'cumulative-layout-shift' => ['numericValue' => 0.12],
                    'total-blocking-time' => ['numericValue' => 200.0],
                    'speed-index' => ['numericValue' => 1500.0],
                ],
            ],
        ], 200);
    });

    $service = app(PageSpeedInsightsService::class);

    $result = $service->fetchPerformance('https://example.com', 'mobile');

    expect($capturedRequest)->not->toBeNull();
    expect($capturedRequest->method())->toBe('GET');
    expect($capturedRequest->url())->toContain('https://www.googleapis.com/pagespeedonline/v5/runPagespeed');
    expect($capturedRequest->url())->toContain('strategy=mobile');
    expect($capturedRequest->url())->toContain('category=performance');
    expect($capturedRequest->url())->toContain('url=https%3A%2F%2Fexample.com');
    expect($capturedRequest->url())->toContain('key=test-key');

    expect($result['url'])->toBe('https://example.com');
    expect($result['strategy'])->toBe('mobile');
    expect($result['score'])->toBe(92);
    expect($result['metrics']['fcpMs'])->toBe(1234);
    expect($result['metrics']['lcpMs'])->toBe(2345);
    expect($result['metrics']['cls'])->toBe(0.12);
    expect($result['metrics']['tbtMs'])->toBe(200);
    expect($result['metrics']['speedIndexMs'])->toBe(1500);
});

test('pagespeed insights service can fetch multiple strategies', function () {
    config()->set('services.pagespeed_insights.key', null);
    config()->set('services.pagespeed_insights.base_url', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed');

    Http::fake([
        'www.googleapis.com/pagespeedonline/v5/runPagespeed*' => Http::response([
            'lighthouseResult' => [
                'categories' => [
                    'performance' => [
                        'score' => 0.5,
                    ],
                ],
                'audits' => [],
            ],
        ], 200),
    ]);

    $service = app(PageSpeedInsightsService::class);

    $result = $service->fetchPerformanceForStrategies('https://example.com', ['mobile', 'desktop']);

    expect($result)->toHaveKeys(['mobile', 'desktop']);
    expect($result['mobile']['score'])->toBe(50);
    expect($result['desktop']['score'])->toBe(50);
});
