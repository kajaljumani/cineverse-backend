<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSwipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_swipe_right_records_like_but_does_not_add_to_watchlist()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'like',
        ]);

        $response->assertStatus(200);

        // Assert interaction recorded
        $this->assertDatabaseHas('interactions', [
            'user_id' => $user->id,
            'media_id' => $media->id,
            'type' => 'like',
        ]);

        // Assert NOT in watchlist
        $this->assertDatabaseMissing('watchlist', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }

    public function test_swipe_left_records_dislike_and_excludes_from_feed()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['popularity' => 100]); // High popularity to ensure it would be in feed

        // Initial check: should be in feed
        $response = $this->actingAs($user)->getJson('/api/swipe');
        $response->assertJsonFragment(['id' => $media->id]);

        // Swipe Left (Dislike)
        $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'dislike',
        ]);

        // Check feed again: should be gone
        $response = $this->actingAs($user)->getJson('/api/swipe');
        $response->assertJsonMissing(['id' => $media->id]);
    }

    public function test_liked_titles_do_not_reappear_in_feed()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['popularity' => 100]);

        // Like
        $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'like',
        ]);

        // Check feed: should be gone
        $response = $this->actingAs($user)->getJson('/api/swipe');
        $response->assertJsonMissing(['id' => $media->id]);
    }

    public function test_watched_titles_do_not_appear_in_feed()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['popularity' => 100]);

        // Mark as watched
        $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'watched',
        ]);

        // Check feed: should be gone
        $response = $this->actingAs($user)->getJson('/api/swipe');
        $response->assertJsonMissing(['id' => $media->id]);
    }

    public function test_interaction_is_idempotent()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // Like once
        $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'like',
        ]);

        // Like again
        $response = $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'like',
        ]);

        $response->assertStatus(200);
        
        // Should still be only one interaction record
        $this->assertCount(1, $user->interactions);
    }
}
