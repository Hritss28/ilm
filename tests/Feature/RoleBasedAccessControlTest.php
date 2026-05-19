<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // Admin Access Tests
    // ========================================

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/categories');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_advertisements(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/advertisements');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/pages');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_videos(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/videos');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_galleries(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/galleries');

        $response->assertStatus(200);
    }

    // ========================================
    // Redaktur Access Tests
    // ========================================

    public function test_redaktur_can_access_dashboard(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_redaktur_can_access_videos(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/videos');

        $response->assertStatus(200);
    }

    public function test_redaktur_can_access_galleries(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/galleries');

        $response->assertStatus(200);
    }

    public function test_redaktur_cannot_access_categories(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/categories');

        $response->assertStatus(403);
    }

    public function test_redaktur_cannot_access_advertisements(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/advertisements');

        $response->assertStatus(403);
    }

    public function test_redaktur_cannot_access_users(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_redaktur_cannot_access_pages(): void
    {
        $redaktur = User::factory()->create(['role' => 'redaktur']);

        $response = $this->actingAs($redaktur)->get('/admin/pages');

        $response->assertStatus(403);
    }

    // ========================================
    // Author Access Tests
    // ========================================

    public function test_author_can_access_dashboard(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_author_cannot_access_categories(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/categories');

        $response->assertStatus(403);
    }

    public function test_author_cannot_access_advertisements(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/advertisements');

        $response->assertStatus(403);
    }

    public function test_author_cannot_access_users(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_author_cannot_access_pages(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/pages');

        $response->assertStatus(403);
    }

    public function test_author_cannot_access_videos(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/videos');

        $response->assertStatus(403);
    }

    public function test_author_cannot_access_galleries(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $response = $this->actingAs($author)->get('/admin/galleries');

        $response->assertStatus(403);
    }

    // ========================================
    // Guest Access Tests
    // ========================================

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_categories(): void
    {
        $response = $this->get('/admin/categories');

        $response->assertRedirect('/login');
    }
}
