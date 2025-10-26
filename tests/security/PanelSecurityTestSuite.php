<?php

namespace Tests\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;

class PanelSecurityTestSuite extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /** @test */
    public function staff_panel_requires_staff_role()
    {
        // Test client role access to staff panel
        $client = User::factory()->create(['role' => 'Client']);
        
        $response = $this->actingAs($client)->get('/staff');
        
        $this->assertEquals(403, $response->status());
        $this->assertStringContainsString('Access denied', $response->getContent());
    }

    /** @test */
    public function admin_panel_requires_admin_role()
    {
        // Test staff role access to admin panel
        $staff = User::factory()->create(['role' => 'Staff']);
        
        $response = $this->actingAs($staff)->get('/admin');
        
        $this->assertEquals(403, $response->status());
        $this->assertStringContainsString('Admin privileges required', $response->getContent());
    }

    /** @test */
    public function staff_panel_blocks_unauthorized_access()
    {
        $unauthorizedTests = [
            '/staff',
            '/staff/dashboard',
            '/staff/users',
            '/staff/appointments',
            '/staff/bills'
        ];

        foreach ($unauthorizedTests as $endpoint) {
            $response = $this->get($endpoint);
            
            $this->assertEquals(302, $response->status()); // Redirect to login
        }
    }

    /** @test */
    public function admin_panel_blocks_unauthorized_access()
    {
        $unauthorizedTests = [
            '/admin',
            '/admin/dashboard',
            '/admin/users',
            '/admin/reports',
            '/admin/settings'
        ];

        foreach ($unauthorizedTests as $endpoint) {
            $response = $this->get($endpoint);
            
            $this->assertEquals(302, $response->status()); // Redirect to login
        }
    }

    /** @test */
    public function staff_panel_has_security_headers()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        
        $response = $this->actingAs($staff)->get('/staff');
        
        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertEquals('DENY', $response->headers->get('X-Frame-Options'));
        $this->assertEquals('1; mode=block', $response->headers->get('X-XSS-Protection'));
        $this->assertEquals('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    /** @test */
    public function admin_panel_has_enhanced_security_headers()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        
        $response = $this->actingAs($admin)->get('/admin');
        
        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertEquals('DENY', $response->headers->get('X-Frame-Options'));
        $this->assertEquals('1; mode=block', $response->headers->get('X-XSS-Protection'));
        $this->assertEquals('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    /** @test */
    public function staff_panel_blocks_large_requests()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        $largePayload = str_repeat('x', 2 * 1024 * 1024); // 2MB

        $response = $this->actingAs($staff)->postJson('/staff/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $largePayload
        ]);

        $this->assertEquals(413, $response->status());
    }

    /** @test */
    public function admin_panel_blocks_large_requests()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $largePayload = str_repeat('x', 2 * 1024 * 1024); // 2MB

        $response = $this->actingAs($admin)->postJson('/admin/users', [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => $largePayload,
            'role' => 'Admin'
        ]);

        $this->assertEquals(413, $response->status());
    }

    /** @test */
    public function staff_panel_logs_sensitive_operations()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        
        // Clear log file
        file_put_contents(storage_path('logs/laravel.log'), '');
        
        $response = $this->actingAs($staff)->postJson('/staff/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'Client'
        ]);

        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        
        $this->assertStringContainsString('Sensitive operation performed', $logContent);
        $this->assertStringContainsString('user_id', $logContent);
        $this->assertStringNotContainsString('password123', $logContent); // Password should be redacted
    }

    /** @test */
    public function admin_panel_logs_all_operations()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        
        // Clear log file
        file_put_contents(storage_path('logs/laravel.log'), '');
        
        $response = $this->actingAs($admin)->get('/admin/users');
        
        $logContent = file_get_contents(storage_path('logs/laravel.log'));
        
        $this->assertStringContainsString('Admin panel access', $logContent);
        $this->assertStringContainsString('user_id', $logContent);
        $this->assertStringContainsString('ip_address', $logContent);
    }

    /** @test */
    public function staff_panel_prevents_sql_injection()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        
        $injectionAttempts = [
            "admin' OR '1'='1",
            "'; DROP TABLE users; --",
            "' UNION SELECT * FROM users --"
        ];

        foreach ($injectionAttempts as $injection) {
            $response = $this->actingAs($staff)->postJson('/staff/users', [
                'name' => $injection,
                'email' => 'test@example.com',
                'password' => 'password123',
                'role' => 'Client'
            ]);

            $this->assertEquals(422, $response->status()); // Validation error, not SQL injection
        }
    }

    /** @test */
    public function admin_panel_prevents_sql_injection()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        
        $injectionAttempts = [
            "admin' OR '1'='1",
            "'; DROP TABLE users; --",
            "' UNION SELECT * FROM users --"
        ];

        foreach ($injectionAttempts as $injection) {
            $response = $this->actingAs($admin)->postJson('/admin/users', [
                'name' => $injection,
                'email' => 'admin@example.com',
                'password' => 'password123',
                'role' => 'Admin'
            ]);

            $this->assertEquals(422, $response->status()); // Validation error, not SQL injection
        }
    }

    /** @test */
    public function staff_panel_prevents_xss_attacks()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        
        $xssAttempts = [
            '<script>alert("xss")</script>',
            '"><script>alert("xss")</script>',
            'javascript:alert("xss")'
        ];

        foreach ($xssAttempts as $xss) {
            $response = $this->actingAs($staff)->postJson('/staff/users', [
                'name' => $xss,
                'email' => 'test@example.com',
                'password' => 'password123',
                'role' => 'Client'
            ]);

            $this->assertStringNotContainsString('<script>', $response->getContent());
        }
    }

    /** @test */
    public function admin_panel_prevents_xss_attacks()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        
        $xssAttempts = [
            '<script>alert("xss")</script>',
            '"><script>alert("xss")</script>',
            'javascript:alert("xss")'
        ];

        foreach ($xssAttempts as $xss) {
            $response = $this->actingAs($admin)->postJson('/admin/users', [
                'name' => $xss,
                'email' => 'admin@example.com',
                'password' => 'password123',
                'role' => 'Admin'
            ]);

            $this->assertStringNotContainsString('<script>', $response->getContent());
        }
    }

    /** @test */
    public function staff_panel_has_rate_limiting()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        
        // Make multiple requests quickly
        for ($i = 0; $i < 15; $i++) {
            $response = $this->actingAs($staff)->get('/staff/users');
            
            if ($i > 10) {
                $this->assertEquals(429, $response->status());
            }
        }
    }

    /** @test */
    public function admin_panel_has_rate_limiting()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        
        // Make multiple requests quickly
        for ($i = 0; $i < 15; $i++) {
            $response = $this->actingAs($admin)->get('/admin/users');
            
            if ($i > 10) {
                $this->assertEquals(429, $response->status());
            }
        }
    }

    /** @test */
    public function staff_panel_requires_secure_session()
    {
        $staff = User::factory()->create(['role' => 'Staff']);
        
        $response = $this->actingAs($staff)->get('/staff');
        
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === config('session.cookie')) {
                $this->assertTrue($cookie->isHttpOnly());
                $this->assertTrue($cookie->isSecure());
                $this->assertEquals('strict', $cookie->getSameSite());
            }
        }
    }

    /** @test */
    public function admin_panel_requires_secure_session()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        
        $response = $this->actingAs($admin)->get('/admin');
        
        $cookies = $response->headers->getCookies();
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === config('session.cookie')) {
                $this->assertTrue($cookie->isHttpOnly());
                $this->assertTrue($cookie->isSecure());
                $this->assertEquals('strict', $cookie->getSameSite());
            }
        }
    }
}
