<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_page_and_submission_are_not_available(): void
    {
        $this->get('/register')->assertNotFound();

        $this->post('/register', [
            'name' => 'Unauthorized User',
            'email' => 'unauthorized@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ])->assertNotFound();

        $this->assertDatabaseMissing('users', ['email' => 'unauthorized@example.com']);
    }

    public function test_login_page_does_not_offer_public_registration(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertDontSee('Register Hada')
            ->assertDontSee('Register');
    }
}
