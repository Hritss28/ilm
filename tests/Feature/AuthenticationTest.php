<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_redirected_to_admin_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_redaktur_redirected_to_admin_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'role' => 'redaktur',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_author_redirected_to_admin_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'role' => 'author',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_login_rate_limiting_after_5_failed_attempts(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        // Make 5 failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        // The error message should contain throttle information
        $errors = session('errors');
        $this->assertNotNull($errors);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }
}
