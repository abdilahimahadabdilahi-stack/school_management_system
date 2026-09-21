<?php

namespace Tests\Feature;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_view_managers_index(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('managers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('managers.index');
    }

    public function test_authenticated_admin_can_create_manager(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('managers.store'), [
            'name' => 'Manager Hassan',
            'email' => 'hassan.manager@example.com',
            'phone' => '1122334455',
            'department' => 'Academic Affairs',
        ]);

        $response->assertRedirect(route('managers.index'));
        $this->assertDatabaseHas('managers', [
            'name' => 'Manager Hassan',
            'email' => 'hassan.manager@example.com',
            'department' => 'Academic Affairs',
        ]);
    }

    public function test_authenticated_admin_can_update_manager(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $manager = Manager::create([
            'name' => 'Manager Aisha',
            'email' => 'aisha@example.com',
            'phone' => '5544332211',
            'department' => 'Finance',
        ]);

        $response = $this->actingAs($user)->put(route('managers.update', $manager->id), [
            'name' => 'Manager Aisha Updated',
            'email' => 'aisha@example.com',
            'phone' => '5544332211',
            'department' => 'Finance & HR',
        ]);

        $response->assertRedirect(route('managers.index'));
        $this->assertDatabaseHas('managers', [
            'id' => $manager->id,
            'name' => 'Manager Aisha Updated',
            'department' => 'Finance & HR',
        ]);
    }

    public function test_authenticated_admin_can_delete_manager(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $manager = Manager::create([
            'name' => 'Manager Farah',
            'email' => 'farah@example.com',
        ]);

        $response = $this->actingAs($user)->delete(route('managers.destroy', $manager->id));

        $response->assertRedirect(route('managers.index'));
        $this->assertDatabaseMissing('managers', [
            'id' => $manager->id,
        ]);
    }
}
