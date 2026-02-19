<?php

namespace App\Providers;

use App\Services\PageSpeedInsightsService;
use Illuminate\Support\ServiceProvider;

class PageSpeedProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PageSpeedInsightsService::class, function ($app) {
            return new PageSpeedInsightsService(
                baseUrl: config('services.pagespeed_insights.base_url'),
                key: config('services.pagespeed_insights.key'),
                connectTimeout: config('services.pagespeed_insights.connect_timeout'),
                timeout: config('services.pagespeed_insights.timeout'),
                verify: config('services.pagespeed_insights.verify'),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
