<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--force' => true]);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\AdminSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\ReceptionistSeeder']);
    }

    public function test_admin_can_login()
    {
        $response = $this->post('/login', [
            'email' => 'admin@hotel.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_receptionist_can_login()
    {
        $response = $this->post('/login', [
            'email' => 'recepcion@hotel.com',
            'password' => 'recepcion123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_wrong_password_fails()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@hotel.com',
            'password' => 'wrong',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}