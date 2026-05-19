<?php

namespace App\Services;

use App\Models\Category;
use App\Models\News;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Generate SEO metadata for a news article.
     *
     * Uses custom SEO fields if set (seo_title, seo_description, seo_keywords).
     * Otherwise auto-generates from title/excerpt.
     *
     * @param News $news
     * @return array{title: string, description: string, keywords: string, og_image: string|null, og_type: string, canonical: string}
     */
    public function generateForNews(News $news): array
    {
        $appName = config('app.name', config('news_portal.site.name', 'Info Lantas Mojokerto'));

        // Title: use custom seo_title or auto-generate
        $title = $news->seo_title ?: "{$news->title} | {$appName}";

        // Description: use custom seo_description or auto-generate from excerpt/content
        $description = $news->seo_description ?: $this->generateDescription($news);

        // Keywords: use custom seo_keywords or category name
        $keywords = $news->seo_keywords ?: $this->generateKeywords($news);

        // OG Image: thumbnail URL
        $ogImage = $news->thumbnail ? url(\Illuminate\Support\Facades\Storage::url($news->thumbnail)) : null;

        // Canonical URL
        $canonical = route('news.show', $news->slug);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_image' => $ogImage,
            'og_type' => 'article',
            'canonical' => $canonical,
        ];
    }

    /**
     * Generate SEO metadata for a category page.
     *
     * @param Category $category
     * @return array{title: string, description: string, keywords: string, og_image: string|null, og_type: string, canonical: string}
     */
    public function generateForCategory(Category $category): array
    {
        $appName = config('app.name', config('news_portal.site.name', 'Info Lantas Mojokerto'));

        return [
            'title' => "Berita {$category->name} | {$appName}",
            'description' => "Kumpulan berita terkini kategori {$category->name} di {$appName}",
            'keywords' => strtolower($category->name) . ', berita ' . strtolower($category->name) . ', ' . strtolower($appName),
            'og_image' => null,
            'og_type' => 'website',
            'canonical' => route('news.category', $category->slug),
        ];
    }

    /**
     * Generate generic SEO metadata for static pages.
     *
     * @param string $title
     * @param string $description
     * @return array{title: string, description: string, keywords: string, og_image: string|null, og_type: string, canonical: string}
     */
    public function generateForPage(string $title, string $description = ''): array
    {
        $appName = config('app.name', config('news_portal.site.name', 'Info Lantas Mojokerto'));
        $defaultKeywords = config('news_portal.seo.default_keywords', 'berita mojokerto, info lantas');

        return [
            'title' => "{$title} | {$appName}",
            'description' => $description ?: config('news_portal.site.description', ''),
            'keywords' => $defaultKeywords,
            'og_image' => null,
            'og_type' => 'website',
            'canonical' => url()->current(),
        ];
    }

    /**
     * Auto-generate description from news excerpt or content.
     * Returns first 160 characters.
     */
    protected function generateDescription(News $news): string
    {
        // Use excerpt if available (from the accessor which auto-generates from content)
        $text = $news->getRawOriginal('excerpt');

        if (empty($text)) {
            // Strip HTML tags from content and take first 160 chars
            $text = strip_tags($news->content ?? '');
        }

        return Str::limit($text, 160, '');
    }

    /**
     * Auto-generate keywords from category name.
     */
    protected function generateKeywords(News $news): string
    {
        $keywords = [];

        if ($news->category) {
            $keywords[] = strtolower($news->category->name);
            $keywords[] = 'berita ' . strtolower($news->category->name);
        }

        $keywords[] = 'berita mojokerto';
        $keywords[] = 'info lantas';

        return implode(', ', $keywords);
    }
}
