<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_unauthorized_access_attempt_logs_security_event(): void
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);

        // Teacher role is forbidden from accessing managers index (role:admin,manager)
        $response = $this->actingAs($teacherUser)->get(route('managers.index'));

        $response->assertStatus(403);
        $this->assertDatabaseHas('security_logs', [
            'user_id' => $teacherUser->id,
            'event_type' => 'UNAUTHORIZED_ACCESS_ATTEMPT',
        ]);
    }

    public function test_teachers_cannot_access_payment_routes(): void
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($teacherUser)->get(route('payments.index'));

        $response->assertStatus(403);
    }

    public function test_creating_parent_logs_security_audit_event(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($adminUser)->post(route('parents.store'), [
            'name' => 'Secured Parent Name',
            'email' => 'secured.parent@example.com',
            'phone' => '123456789',
        ]);

        $response->assertRedirect(route('parents.index'));
        $this->assertDatabaseHas('security_logs', [
            'user_id' => $adminUser->id,
            'event_type' => 'CREATE_PARENT',
        ]);
    }
}
