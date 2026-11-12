<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_account_page_offers_admin_teacher_and_staff_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin-users.create'))
            ->assertOk()
            ->assertSee('<option value="admin"', false)
            ->assertSee('<option value="teacher"', false)
            ->assertSee('<option value="staff"', false);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertSee('Register Admin / Teacher / Staff');
    }

    public function test_admin_can_create_an_admin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin-users.store'), [
            'name' => 'New Administrator',
            'email' => 'new.admin@example.com',
            'role' => 'admin',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin-users.create'));
        $this->assertDatabaseHas('users', [
            'name' => 'New Administrator',
            'email' => 'new.admin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_create_a_teacher_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin-users.store'), [
            'name' => 'New Teacher',
            'email' => 'new.teacher@example.com',
            'role' => 'teacher',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin-users.create'));
        $this->assertDatabaseHas('users', [
            'name' => 'New Teacher',
            'email' => 'new.teacher@example.com',
            'role' => 'teacher',
        ]);
    }

    public function test_admin_can_create_a_staff_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin-users.store'), [
            'name' => 'New Staff Member',
            'email' => 'new.staff@example.com',
            'role' => 'staff',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin-users.create'));
        $this->assertDatabaseHas('users', [
            'name' => 'New Staff Member',
            'email' => 'new.staff@example.com',
            'role' => 'staff',
        ]);
    }

    public function test_guests_cannot_access_admin_account_creation(): void
    {
        $this->get(route('admin-users.create'))
            ->assertRedirect(route('login'));

        $this->post(route('admin-users.store'), [
            'name' => 'Unauthorized Administrator',
            'email' => 'guest.admin@example.com',
            'role' => 'admin',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', ['email' => 'guest.admin@example.com']);
    }

    public function test_managers_cannot_create_admin_accounts(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $this->actingAs($manager)
            ->get(route('admin-users.create'))
            ->assertForbidden();

        $this->actingAs($manager)
            ->post(route('admin-users.store'), [
                'name' => 'Unauthorized Administrator',
                'email' => 'manager.admin@example.com',
                'role' => 'admin',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'manager.admin@example.com']);
    }

    public function test_non_admin_users_cannot_open_or_submit_admin_account_registration(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($teacher)
            ->get(route('admin-users.create'))
            ->assertForbidden();

        $this->actingAs($teacher)
            ->post(route('admin-users.store'), [
                'name' => 'Unauthorized Administrator',
                'email' => 'unauthorized.admin@example.com',
                'role' => 'admin',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'email' => 'unauthorized.admin@example.com',
        ]);
    }

    public function test_admin_account_registration_rejects_other_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->from(route('admin-users.create'))
            ->post(route('admin-users.store'), [
                'name' => 'Invalid Role User',
                'email' => 'invalid.role@example.com',
                'role' => 'manager',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ]);

        $response->assertRedirect(route('admin-users.create'))
            ->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid.role@example.com',
        ]);
    }
}
