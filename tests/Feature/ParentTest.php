<?php

namespace Tests\Feature;

use App\Models\SchoolParent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_view_parents_index(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('parents.index'));

        $response->assertStatus(200);
        $response->assertViewIs('parents.index');
    }

    public function test_authenticated_admin_can_create_parent(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('parents.store'), [
            'name' => 'Ali Mohamed',
            'email' => 'ali@example.com',
            'phone' => '123456789',
            'occupation' => 'Doctor',
            'address' => 'Mogadishu, Somalia',
        ]);

        $response->assertRedirect(route('parents.index'));
        $this->assertDatabaseHas('parents', [
            'name' => 'Ali Mohamed',
            'email' => 'ali@example.com',
        ]);
    }

    public function test_authenticated_admin_can_update_parent(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $parent = SchoolParent::create([
            'name' => 'Fatima Hassan',
            'email' => 'fatima@example.com',
            'phone' => '987654321',
            'occupation' => 'Engineer',
            'address' => 'Hargeisa',
        ]);

        $response = $this->actingAs($user)->put(route('parents.update', $parent->id), [
            'name' => 'Fatima Hassan Updated',
            'email' => 'fatima@example.com',
            'phone' => '987654321',
            'occupation' => 'Senior Engineer',
            'address' => 'Hargeisa, Somaliland',
        ]);

        $response->assertRedirect(route('parents.index'));
        $this->assertDatabaseHas('parents', [
            'id' => $parent->id,
            'name' => 'Fatima Hassan Updated',
            'occupation' => 'Senior Engineer',
        ]);
    }

    public function test_authenticated_admin_can_delete_parent(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $parent = SchoolParent::create([
            'name' => 'Ahmed Omar',
            'email' => 'ahmed@example.com',
        ]);

        $response = $this->actingAs($user)->delete(route('parents.destroy', $parent->id));

        $response->assertRedirect(route('parents.index'));
        $this->assertDatabaseMissing('parents', [
            'id' => $parent->id,
        ]);
    }
}
