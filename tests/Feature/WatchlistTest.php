<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_to_watchlist()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/watchlist', [
            'media_id' => $media->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('watchlist', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }

    public function test_adding_duplicate_to_watchlist_returns_200_and_does_not_duplicate()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // Add first time
        $this->actingAs($user)->postJson('/api/watchlist', [
            'media_id' => $media->id,
        ]);

        // Add second time
        $response = $this->actingAs($user)->postJson('/api/watchlist', [
            'media_id' => $media->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Media already in watchlist']);
        
        $this->assertCount(1, $user->watchlist);
    }

    public function test_watchlist_index_can_filter_by_watched()
    {
        $user = User::factory()->create();
        $watched = Media::factory()->create();
        $unwatched = Media::factory()->create();

        $user->watchlist()->create(['media_id' => $watched->id, 'watched_at' => now()]);
        $user->watchlist()->create(['media_id' => $unwatched->id]);

        // Filter: watched=true
        $response = $this->actingAs($user)->getJson('/api/watchlist?watched=1');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['media_id' => $watched->id]);

        // Filter: watched=false
        $response = $this->actingAs($user)->getJson('/api/watchlist?watched=0');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['media_id' => $unwatched->id]);
    }

    public function test_watchlist_addition_does_not_create_interaction()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        $this->actingAs($user)->postJson('/api/watchlist', [
            'media_id' => $media->id,
        ]);

        $this->assertDatabaseMissing('interactions', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }

    public function test_like_interaction_does_not_add_to_watchlist()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        $this->actingAs($user)->postJson('/api/interactions', [
            'media_id' => $media->id,
            'type' => 'like',
        ]);

        $this->assertDatabaseMissing('watchlist', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }
}
