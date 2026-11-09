<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_user_from_the_users_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($admin)
            ->delete(route('reports.users.destroy', $user));

        $response->assertRedirect(route('reports.show', 'users'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_manager_can_delete_a_user_from_the_users_report(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $user = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($manager)
            ->delete(route('reports.users.destroy', $user));

        $response->assertRedirect(route('reports.show', 'users'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_teacher_cannot_delete_a_user_from_the_users_report(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $user = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($teacher)
            ->delete(route('reports.users.destroy', $user))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_their_own_account_from_the_users_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->delete(route('reports.users.destroy', $admin));

        $response->assertRedirect(route('reports.show', 'users'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_users_report_displays_delete_actions_for_each_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($admin)
            ->get(route('reports.show', 'users'))
            ->assertOk()
            ->assertSee('Actions')
            ->assertSee(route('reports.users.destroy', $user), false)
            ->assertSee('Are you sure you want to delete this user?', false)
            ->assertSee('name="_method" value="DELETE"', false)
            ->assertSee('name="_token"', false);
    }
}
