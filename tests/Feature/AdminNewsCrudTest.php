<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNewsCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $redaktur;
    private User $author;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->redaktur = User::factory()->create(['role' => 'redaktur']);
        $this->author = User::factory()->create(['role' => 'author']);
        $this->category = Category::factory()->create();
    }

    // ========================================
    // News Index (Read)
    // ========================================

    public function test_admin_can_view_news_index(): void
    {
        $news = News::factory()->create(['category_id' => $this->category->id, 'author_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index'));

        $response->assertStatus(200);
        $response->assertSee($news->title);
    }

    public function test_redaktur_can_view_news_index(): void
    {
        $response = $this->actingAs($this->redaktur)->get(route('admin.news.index'));

        $response->assertStatus(200);
    }

    public function test_author_can_view_news_index(): void
    {
        $response = $this->actingAs($this->author)->get(route('admin.news.index'));

        $response->assertStatus(200);
    }

    public function test_author_only_sees_own_news(): void
    {
        $ownNews = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'title' => 'My Own Article',
        ]);
        $otherNews = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
            'title' => 'Other Article',
        ]);

        $response = $this->actingAs($this->author)->get(route('admin.news.index'));

        $response->assertStatus(200);
        $response->assertSee('My Own Article');
        $response->assertDontSee('Other Article');
    }

    // ========================================
    // News Create
    // ========================================

    public function test_admin_can_create_news(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Test News Article',
            'category_id' => $this->category->id,
            'content' => '<p>This is test content</p>',
            'status' => 'published',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'title' => 'Test News Article',
            'author_id' => $this->admin->id,
            'status' => 'published',
        ]);
    }

    public function test_redaktur_can_create_news(): void
    {
        $response = $this->actingAs($this->redaktur)->post(route('admin.news.store'), [
            'title' => 'Redaktur News Article',
            'category_id' => $this->category->id,
            'content' => '<p>Content by redaktur</p>',
            'status' => 'published',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'title' => 'Redaktur News Article',
            'author_id' => $this->redaktur->id,
        ]);
    }

    public function test_author_can_create_news(): void
    {
        $response = $this->actingAs($this->author)->post(route('admin.news.store'), [
            'title' => 'Author News Article',
            'category_id' => $this->category->id,
            'content' => '<p>Content by author</p>',
            'status' => 'draft',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'title' => 'Author News Article',
            'author_id' => $this->author->id,
            'status' => 'draft',
        ]);
    }

    public function test_create_news_sets_published_at_when_published(): void
    {
        $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Published Article',
            'category_id' => $this->category->id,
            'content' => '<p>Content</p>',
            'status' => 'published',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $news = News::where('title', 'Published Article')->first();
        $this->assertNotNull($news->published_at);
    }

    public function test_create_news_validation_fails_without_title(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => '',
            'category_id' => $this->category->id,
            'content' => '<p>Content</p>',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_create_news_validation_fails_with_invalid_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), [
            'title' => 'Test Article',
            'category_id' => 9999,
            'content' => '<p>Content</p>',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    // ========================================
    // News Update
    // ========================================

    public function test_admin_can_update_any_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.news.update', $news), [
            'title' => 'Updated Title',
            'category_id' => $this->category->id,
            'content' => '<p>Updated content</p>',
            'status' => 'published',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_redaktur_can_update_any_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->redaktur)->put(route('admin.news.update', $news), [
            'title' => 'Redaktur Updated',
            'category_id' => $this->category->id,
            'content' => '<p>Updated by redaktur</p>',
            'status' => 'published',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertRedirect(route('admin.news.index'));
    }

    public function test_author_can_update_own_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->author)->put(route('admin.news.update', $news), [
            'title' => 'Author Updated Own',
            'category_id' => $this->category->id,
            'content' => '<p>Updated by author</p>',
            'status' => 'draft',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertRedirect(route('admin.news.index'));
    }

    public function test_author_cannot_update_others_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->author)->put(route('admin.news.update', $news), [
            'title' => 'Unauthorized Update',
            'category_id' => $this->category->id,
            'content' => '<p>Should not work</p>',
            'status' => 'draft',
            'is_featured' => false,
            'is_breaking_news' => false,
        ]);

        $response->assertStatus(403);
    }

    // ========================================
    // News Delete
    // ========================================

    public function test_admin_can_delete_any_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.news.destroy', $news));

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_redaktur_can_delete_any_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->redaktur)->delete(route('admin.news.destroy', $news));

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_author_can_delete_own_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->author)->delete(route('admin.news.destroy', $news));

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_author_cannot_delete_others_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->author)->delete(route('admin.news.destroy', $news));

        $response->assertStatus(403);
        $this->assertDatabaseHas('news', ['id' => $news->id]);
    }

    // ========================================
    // Toggle Breaking News
    // ========================================

    public function test_admin_can_toggle_breaking_news_on(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
            'is_breaking_news' => false,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.news.breaking', $news));

        $response->assertRedirect();
        $news->refresh();
        $this->assertTrue($news->is_breaking_news);
        $this->assertNotNull($news->breaking_news_until);
        $this->assertTrue($news->breaking_news_until->greaterThan(now()->addHours(23)));
    }

    public function test_admin_can_toggle_breaking_news_off(): void
    {
        $news = News::factory()->breaking()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.news.breaking', $news));

        $response->assertRedirect();
        $news->refresh();
        $this->assertFalse($news->is_breaking_news);
        $this->assertNull($news->breaking_news_until);
    }

    public function test_redaktur_can_toggle_breaking_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_breaking_news' => false,
        ]);

        $response = $this->actingAs($this->redaktur)->patch(route('admin.news.breaking', $news));

        $response->assertRedirect();
        $news->refresh();
        $this->assertTrue($news->is_breaking_news);
    }

    public function test_author_cannot_toggle_breaking_news(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_breaking_news' => false,
        ]);

        $response = $this->actingAs($this->author)->patch(route('admin.news.breaking', $news));

        $response->assertStatus(403);
    }

    // ========================================
    // Toggle Featured
    // ========================================

    public function test_admin_can_toggle_featured(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.news.featured', $news));

        $response->assertRedirect();
        $news->refresh();
        $this->assertTrue($news->is_featured);
    }

    public function test_redaktur_can_toggle_featured(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->redaktur)->patch(route('admin.news.featured', $news));

        $response->assertRedirect();
        $news->refresh();
        $this->assertTrue($news->is_featured);
    }

    public function test_author_cannot_toggle_featured(): void
    {
        $news = News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->author)->patch(route('admin.news.featured', $news));

        $response->assertStatus(403);
    }

    public function test_toggle_featured_off(): void
    {
        $news = News::factory()->featured()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.news.featured', $news));

        $response->assertRedirect();
        $news->refresh();
        $this->assertFalse($news->is_featured);
    }

    // ========================================
    // Filter Tests
    // ========================================

    public function test_news_index_can_filter_by_category(): void
    {
        $category2 = Category::factory()->create();
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
            'title' => 'Category One News',
        ]);
        News::factory()->create([
            'category_id' => $category2->id,
            'author_id' => $this->admin->id,
            'title' => 'Category Two News',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index', ['category_id' => $this->category->id]));

        $response->assertStatus(200);
        $response->assertSee('Category One News');
        $response->assertDontSee('Category Two News');
    }

    public function test_news_index_can_filter_by_status(): void
    {
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
            'title' => 'Published News',
            'status' => 'published',
        ]);
        News::factory()->create([
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id,
            'title' => 'Draft News',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index', ['status' => 'draft']));

        $response->assertStatus(200);
        $response->assertSee('Draft News');
        $response->assertDontSee('Published News');
    }
}
