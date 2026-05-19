<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\News;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SeoService $seoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seoService = new SeoService();
    }

    // ─── Auto-generate SEO from title/excerpt ────────────────────────────────

    public function test_generates_title_from_news_title_when_no_custom_seo_title(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'title' => 'Berita Penting Hari Ini',
            'seo_title' => null,
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $appName = config('app.name');
        $this->assertEquals("Berita Penting Hari Ini | {$appName}", $seo['title']);
    }

    public function test_generates_description_from_excerpt_when_no_custom_seo_description(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $excerpt = 'Ini adalah ringkasan berita yang cukup panjang untuk digunakan sebagai deskripsi SEO otomatis.';
        $news = News::factory()->create([
            'excerpt' => $excerpt,
            'seo_description' => null,
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertStringContainsString('Ini adalah ringkasan berita', $seo['description']);
    }

    public function test_generates_description_from_content_when_no_excerpt(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $content = '<p>Konten berita yang sangat panjang dan berisi banyak informasi penting tentang kejadian hari ini.</p>';
        $news = News::factory()->create([
            'excerpt' => null,
            'content' => $content,
            'seo_description' => null,
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        // Should strip HTML and use content
        $this->assertStringContainsString('Konten berita yang sangat panjang', $seo['description']);
        $this->assertStringNotContainsString('<p>', $seo['description']);
    }

    public function test_description_is_limited_to_160_characters(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $longExcerpt = str_repeat('Ini adalah teks yang sangat panjang. ', 20);
        $news = News::factory()->create([
            'excerpt' => $longExcerpt,
            'seo_description' => null,
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertLessThanOrEqual(160, strlen($seo['description']));
    }

    public function test_generates_keywords_from_category_name(): void
    {
        $category = Category::create(['name' => 'Olahraga', 'slug' => 'olahraga', 'order' => 1]);
        $news = News::factory()->create([
            'seo_keywords' => null,
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertStringContainsString('olahraga', $seo['keywords']);
    }

    public function test_og_type_is_article_for_news(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create(['category_id' => $category->id]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertEquals('article', $seo['og_type']);
    }

    public function test_canonical_url_uses_news_show_route(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'slug' => 'berita-penting',
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertStringContainsString('berita-penting', $seo['canonical']);
    }

    // ─── Custom SEO Metadata Override ────────────────────────────────────────

    public function test_uses_custom_seo_title_when_set(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'title' => 'Original Title',
            'seo_title' => 'Custom SEO Title',
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertEquals('Custom SEO Title', $seo['title']);
    }

    public function test_uses_custom_seo_description_when_set(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'seo_description' => 'Custom meta description for SEO',
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertEquals('Custom meta description for SEO', $seo['description']);
    }

    public function test_uses_custom_seo_keywords_when_set(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'seo_keywords' => 'custom, keywords, seo',
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertEquals('custom, keywords, seo', $seo['keywords']);
    }

    public function test_og_image_uses_thumbnail_url(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'thumbnail' => 'thumbnails/2024/01/test.webp',
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertNotNull($seo['og_image']);
        $this->assertStringContainsString('thumbnails/2024/01/test.webp', $seo['og_image']);
    }

    public function test_og_image_is_null_when_no_thumbnail(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional', 'order' => 1]);
        $news = News::factory()->create([
            'thumbnail' => null,
            'category_id' => $category->id,
        ]);

        $seo = $this->seoService->generateForNews($news);

        $this->assertNull($seo['og_image']);
    }

    // ─── Category SEO ────────────────────────────────────────────────────────

    public function test_generate_for_category(): void
    {
        $category = Category::create(['name' => 'Politik', 'slug' => 'politik', 'order' => 1]);

        $seo = $this->seoService->generateForCategory($category);

        $this->assertStringContainsString('Politik', $seo['title']);
        $this->assertStringContainsString('politik', $seo['keywords']);
        $this->assertEquals('website', $seo['og_type']);
        $this->assertStringContainsString('politik', $seo['canonical']);
    }

    // ─── Static Page SEO ─────────────────────────────────────────────────────

    public function test_generate_for_page(): void
    {
        $seo = $this->seoService->generateForPage('Tentang Kami', 'Halaman tentang kami');

        $appName = config('app.name');
        $this->assertEquals("Tentang Kami | {$appName}", $seo['title']);
        $this->assertEquals('Halaman tentang kami', $seo['description']);
        $this->assertEquals('website', $seo['og_type']);
    }

    public function test_generate_for_page_uses_default_description_when_empty(): void
    {
        $seo = $this->seoService->generateForPage('Tentang Kami');

        $this->assertNotEmpty($seo['description']);
    }
}
