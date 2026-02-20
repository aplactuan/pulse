<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Services\PageSpeedInsightsService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class PageSpeedAnalysisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:page-speed-analysis-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PageSpeedInsightsService $pageSpeedInsightsService): int
    {
        // get all sites from the database but chunk them by 100
        Site::query()->chunk(100, function (Collection $sites) use ($pageSpeedInsightsService) {
            foreach ($sites as $site) {
                $desktopPerformance = $pageSpeedInsightsService->fetchPerformance($site->url, 'desktop');
                $mobilePerformance = $pageSpeedInsightsService->fetchPerformance($site->url, 'mobile');
                $this->info('Performance: '.$desktopPerformance['score']);
                $this->info('Performance: '.$mobilePerformance['score']);
                $site->update([
                    'pagespeed_desktop_score' => $desktopPerformance['score'],
                    'pagespeed_mobile_score' => $mobilePerformance['score'],
                ]);
            }
        });

        return Command::SUCCESS;
    }
}
