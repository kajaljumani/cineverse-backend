<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwipeUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Swipe Up (Mark as Watched) functionality.
     */
    public function test_swipe_up_action_adds_to_watched_list()
    {
        // 1. Setup
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // 2. Simulate Swipe Up -> Interaction 'watched'
        $response = $this->actingAs($user)
            ->postJson('/api/interactions', [
                'media_id' => $media->id,
                'type' => 'watched', // Swipe Up maps to this
            ]);

        // 3. Assert Response
        $response->assertStatus(200);

        // 4. Assert Interaction Recorded
        $this->assertDatabaseHas('interactions', [
            'user_id' => $user->id,
            'media_id' => $media->id,
            'type' => 'watched',
        ]);

        // 5. Assert Added to Watchlist & Marked Watched
        $this->assertDatabaseHas('watchlist', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
        
        // Check watched_at is set
        $watchlistEntry = $user->watchlist()->where('media_id', $media->id)->first();
        $this->assertNotNull($watchlistEntry->watched_at, 'Swipe Up should set watched_at timestamp');
    }

    /**
     * Test Center Button (Add to Watchlist) functionality for comparison.
     */
    public function test_center_button_adds_to_watchlist_queue()
    {
        // 1. Setup
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // 2. Simulate Center Button -> Add to Watchlist
        $response = $this->actingAs($user)
            ->postJson('/api/watchlist', [
                'media_id' => $media->id,
            ]);

        // 3. Assert Response
        $response->assertStatus(201); // Created

        // 4. Assert Added to Watchlist
        $this->assertDatabaseHas('watchlist', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);

        // Check watched_at is NULL (Queue)
        $watchlistEntry = $user->watchlist()->where('media_id', $media->id)->first();
        $this->assertNull($watchlistEntry->watched_at, 'Center button should NOT set watched_at timestamp initially');
    }
}
