<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFrontendTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private User $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create(['name' => 'Nasional', 'slug' => 'nasional']);
        $this->author = User::factory()->create(['role' => 'author']);
    }

    // ========================================
    // Homepage Tests
    // ========================================

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_homepage_displays_featured_news(): void
    {
        $featured = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'is_featured' => true,
            'title' => 'Featured Article Title',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Featured Article Title');
    }

    // ========================================
    // News Detail & View Counter Tests
    // ========================================

    public function test_news_detail_page_loads_successfully(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'slug' => 'test-article-detail',
            'title' => 'Test Article Detail',
        ]);

        $response = $this->get(route('news.show', 'test-article-detail'));

        $response->assertStatus(200);
        $response->assertSee('Test Article Detail');
    }

    public function test_news_detail_increments_view_counter(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'slug' => 'view-counter-test',
            'views' => 10,
        ]);

        $this->get(route('news.show', 'view-counter-test'));

        $news->refresh();
        $this->assertEquals(11, $news->views);
    }

    public function test_news_detail_does_not_double_count_views_in_same_session(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'slug' => 'no-double-count',
            'views' => 5,
        ]);

        // First visit
        $this->withSession([])->get(route('news.show', 'no-double-count'));
        // Second visit in same session
        $this->get(route('news.show', 'no-double-count'));

        $news->refresh();
        $this->assertEquals(6, $news->views);
    }

    public function test_news_detail_returns_404_for_nonexistent_slug(): void
    {
        $response = $this->get(route('news.show', 'nonexistent-article'));

        $response->assertStatus(404);
    }

    public function test_news_detail_returns_404_for_draft_article(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'draft',
            'slug' => 'draft-article',
        ]);

        $response = $this->get(route('news.show', 'draft-article'));

        $response->assertStatus(404);
    }

    // ========================================
    // Category Page & Pagination Tests
    // ========================================

    public function test_category_page_loads_successfully(): void
    {
        $response = $this->get(route('news.category', 'nasional'));

        $response->assertStatus(200);
    }

    public function test_category_page_shows_news_from_category(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'title' => 'Nasional News Article',
        ]);

        $response = $this->get(route('news.category', 'nasional'));

        $response->assertStatus(200);
        $response->assertSee('Nasional News Article');
    }

    public function test_category_page_returns_404_for_nonexistent_category(): void
    {
        $response = $this->get(route('news.category', 'nonexistent-category'));

        $response->assertStatus(404);
    }

    public function test_category_page_paginates_results(): void
    {
        // Create more than 15 articles (default pagination)
        News::factory()->count(20)->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
        ]);

        $response = $this->get(route('news.category', 'nasional'));

        $response->assertStatus(200);

        // Check page 2 exists
        $responsePage2 = $this->get(route('news.category', 'nasional') . '?page=2');
        $responsePage2->assertStatus(200);
    }

    // ========================================
    // Search Tests
    // ========================================

    public function test_search_page_loads_successfully(): void
    {
        $response = $this->get(route('news.search'));

        $response->assertStatus(200);
    }

    public function test_search_finds_news_by_title(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'title' => 'Kecelakaan di Jalan Raya Mojokerto',
        ]);

        $response = $this->get(route('news.search', ['q' => 'Kecelakaan']));

        $response->assertStatus(200);
        $response->assertSee('Kecelakaan di Jalan Raya Mojokerto');
    }

    public function test_search_finds_news_by_content(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'title' => 'Berita Umum',
            'content' => '<p>Informasi tentang pembangunan jembatan baru di Mojokerto</p>',
        ]);

        $response = $this->get(route('news.search', ['q' => 'jembatan']));

        $response->assertStatus(200);
        $response->assertSee('Berita Umum');
    }

    public function test_search_does_not_find_draft_articles(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'draft',
            'title' => 'Draft Article Should Not Appear',
        ]);

        $response = $this->get(route('news.search', ['q' => 'Draft Article']));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Article Should Not Appear');
    }

    public function test_search_with_empty_query_shows_prompt(): void
    {
        $response = $this->get(route('news.search', ['q' => '']));

        $response->assertStatus(200);
        $response->assertSee('Masukkan kata kunci untuk mencari berita');
    }
}
