<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_response_includes_user_status_fields()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // Add to watchlist
        $user->watchlist()->create(['media_id' => $media->id]);
        
        // Add interaction
        $user->interactions()->create(['media_id' => $media->id, 'type' => 'like']);

        $response = $this->actingAs($user)->getJson("/api/media/{$media->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $media->id,
                    'is_in_watchlist' => true,
                    'user_interaction_status' => 'like',
                ]
            ]);
    }

    public function test_feed_response_includes_user_status_fields()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['popularity' => 1000]); // Ensure it appears in trending

        // Add to watchlist
        $user->watchlist()->create(['media_id' => $media->id]);

        $response = $this->actingAs($user)->getJson("/api/feed");

        $response->assertStatus(200);
        
        // Check trending section
        $trending = $response->json('trending');
        $found = false;
        foreach ($trending as $item) {
            if ($item['id'] === $media->id) {
                $found = true;
                $this->assertTrue($item['is_in_watchlist']);
                break;
            }
        }
        $this->assertTrue($found, 'Media not found in trending feed');
    }

    public function test_swipe_response_includes_user_status_fields()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // Note: Swipe feed excludes interacted media, so we only test watchlist here
        // But wait, if I interact, it disappears from swipe feed.
        // So I can only test "is_in_watchlist" if I added it manually without interaction (which is possible via API)
        
        $user->watchlist()->create(['media_id' => $media->id]);

        $response = $this->actingAs($user)->getJson("/api/swipe");

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $found = false;
        foreach ($data as $item) {
            if ($item['id'] === $media->id) {
                $found = true;
                $this->assertTrue($item['is_in_watchlist']);
                break;
            }
        }
        $this->assertTrue($found, 'Media not found in swipe feed');
    }
}
