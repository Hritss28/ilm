<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsScopeTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private User $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create(['slug' => 'nasional']);
        $this->author = User::factory()->create(['role' => 'author']);
    }

    // ========================================
    // Published Scope
    // ========================================

    public function test_published_scope_returns_only_published_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
        ]);
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'draft',
        ]);
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'hidden',
        ]);

        $published = News::published()->get();

        $this->assertCount(1, $published);
        $this->assertEquals('published', $published->first()->status);
    }

    public function test_published_scope_returns_empty_when_no_published_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'draft',
        ]);

        $published = News::published()->get();

        $this->assertCount(0, $published);
    }

    // ========================================
    // Featured Scope
    // ========================================

    public function test_featured_scope_returns_only_featured_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_featured' => true,
        ]);
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_featured' => false,
        ]);

        $featured = News::featured()->get();

        $this->assertCount(1, $featured);
        $this->assertTrue($featured->first()->is_featured);
    }

    public function test_featured_scope_returns_empty_when_no_featured_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_featured' => false,
        ]);

        $featured = News::featured()->get();

        $this->assertCount(0, $featured);
    }

    // ========================================
    // Breaking News Scope
    // ========================================

    public function test_breaking_news_scope_returns_active_breaking_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_breaking_news' => true,
            'breaking_news_until' => now()->addHours(12),
        ]);

        $breaking = News::breakingNews()->get();

        $this->assertCount(1, $breaking);
        $this->assertTrue($breaking->first()->is_breaking_news);
    }

    public function test_breaking_news_scope_excludes_expired_breaking_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_breaking_news' => true,
            'breaking_news_until' => now()->subHours(1),
        ]);

        $breaking = News::breakingNews()->get();

        $this->assertCount(0, $breaking);
    }

    public function test_breaking_news_scope_includes_news_with_null_breaking_until(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_breaking_news' => true,
            'breaking_news_until' => null,
        ]);

        $breaking = News::breakingNews()->get();

        $this->assertCount(1, $breaking);
    }

    public function test_breaking_news_scope_excludes_non_breaking_news(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_breaking_news' => false,
        ]);

        $breaking = News::breakingNews()->get();

        $this->assertCount(0, $breaking);
    }

    // ========================================
    // By Category Scope
    // ========================================

    public function test_by_category_scope_filters_by_category_slug(): void
    {
        $otherCategory = Category::factory()->create(['slug' => 'olahraga']);

        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);
        News::factory()->create([
            'category_id' => $otherCategory->id,
            'author_id' => $this->author->id,
        ]);

        $filtered = News::byCategory('nasional')->get();

        $this->assertCount(1, $filtered);
        $this->assertEquals($this->category->id, $filtered->first()->category_id);
    }

    public function test_by_category_scope_returns_empty_for_nonexistent_category(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $filtered = News::byCategory('nonexistent-slug')->get();

        $this->assertCount(0, $filtered);
    }

    public function test_by_category_scope_returns_multiple_news_in_same_category(): void
    {
        News::factory()->count(3)->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $filtered = News::byCategory('nasional')->get();

        $this->assertCount(3, $filtered);
    }

    // ========================================
    // Combined Scopes
    // ========================================

    public function test_can_combine_published_and_featured_scopes(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
            'is_featured' => true,
        ]);
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'draft',
            'is_featured' => true,
        ]);

        $result = News::published()->featured()->get();

        $this->assertCount(1, $result);
    }

    public function test_can_combine_published_and_by_category_scopes(): void
    {
        $otherCategory = Category::factory()->create(['slug' => 'politik']);

        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => 'published',
        ]);
        News::factory()->create([
            'category_id' => $otherCategory->id,
            'author_id' => $this->author->id,
            'status' => 'published',
        ]);

        $result = News::published()->byCategory('nasional')->get();

        $this->assertCount(1, $result);
    }
}
