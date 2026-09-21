<?php

namespace Tests\Feature;

use App\Models\SchoolParent;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_is_saved_and_emailed_to_selected_parent(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $parent = SchoolParent::create([
            'name' => 'Amina Hassan',
            'email' => 'amina@example.com',
        ]);

        $response = $this->actingAs($admin)->post(route('announcements.store'), [
            'parent_id' => $parent->id,
            'title' => 'School Closure',
            'message' => 'School will be closed tomorrow.',
        ]);

        $response->assertRedirect(route('announcements.index'));
        $this->assertDatabaseHas('announcements', [
            'parent_id' => $parent->id,
            'title' => 'School Closure',
            'message' => 'School will be closed tomorrow.',
        ]);
        Notification::assertSentOnDemand(AnnouncementNotification::class, function (AnnouncementNotification $notification, array $channels, object $notifiable): bool {
            return $channels === ['mail']
                && $notifiable->routes['mail'] === 'amina@example.com'
                && $notification->announcement->title === 'School Closure';
        });
    }

    public function test_global_announcement_is_emailed_to_every_parent(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        SchoolParent::create(['name' => 'Parent One', 'email' => 'one@example.com']);
        SchoolParent::create(['name' => 'Parent Two', 'email' => 'two@example.com']);

        $this->actingAs($admin)->post(route('announcements.store'), [
            'title' => 'Holiday Notice',
            'message' => 'School is closed on Friday.',
        ]);

        Notification::assertSentOnDemandTimes(AnnouncementNotification::class, 2);
    }
}
