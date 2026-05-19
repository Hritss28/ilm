<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Services\CacheService;
use Illuminate\Console\Command;

class CleanBreakingNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'news:clean-breaking';

    /**
     * The console command description.
     */
    protected $description = 'Set is_breaking_news = false for all news where breaking_news_until has passed';

    /**
     * Execute the console command.
     */
    public function handle(CacheService $cacheService): int
    {
        $expiredCount = News::query()
            ->where('is_breaking_news', true)
            ->where('breaking_news_until', '<', now())
            ->update([
                'is_breaking_news' => false,
                'breaking_news_until' => null,
            ]);

        if ($expiredCount > 0) {
            $cacheService->flushNewsCache();
            $this->info("Cleaned {$expiredCount} expired breaking news item(s). Cache flushed.");
        } else {
            $this->info('No expired breaking news found.');
        }

        return Command::SUCCESS;
    }
}
