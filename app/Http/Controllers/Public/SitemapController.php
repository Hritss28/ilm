<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\StaticPage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap containing all published news, category pages, and static pages.
     * Cached for 24 hours.
     */
    public function index(): Response
    {
        $content = Cache::remember('sitemap_xml', now()->addHours(24), function () {
            return $this->generateSitemap();
        });

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate the XML sitemap content.
     */
    protected function generateSitemap(): string
    {
        $urls = [];

        // Homepage
        $urls[] = [
            'loc' => route('home'),
            'changefreq' => 'hourly',
            'priority' => '1.0',
        ];

        // Published news articles
        $news = News::query()
            ->published()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('published_at')
            ->get();

        foreach ($news as $article) {
            $urls[] = [
                'loc' => route('news.show', $article->slug),
                'lastmod' => $article->updated_at->toW3cString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ];
        }

        // Category pages
        $categories = Category::select(['slug', 'updated_at'])->get();

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('news.category', $category->slug),
                'lastmod' => $category->updated_at->toW3cString(),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ];
        }

        // Static pages
        $urls[] = [
            'loc' => route('about'),
            'changefreq' => 'monthly',
            'priority' => '0.5',
        ];

        $urls[] = [
            'loc' => route('redaksi'),
            'changefreq' => 'monthly',
            'priority' => '0.5',
        ];

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
