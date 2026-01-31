<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_save_preferences()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/preferences', [
            'content_type' => 'tv',
            'genres' => [28, 35],
            'services' => ['netflix', 'prime'],
            'min_rating' => 8.5,
            'release_year_start' => 2010,
            'release_year_end' => 2024,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'content_type' => 'tv',
            'min_rating' => 8.5,
            'services' => ['netflix', 'prime'],
        ]);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'content_type' => 'tv',
            'min_rating' => 8.5,
        ]);
    }

    public function test_swipe_feed_is_filtered_by_preferences()
    {
        $user = User::factory()->create();
        
        // Create preferences: Only TV shows, Rating >= 8
        UserPreference::create([
            'user_id' => $user->id,
            'content_type' => 'tv',
            'min_rating' => 8.0,
            'genres' => [],
            'providers' => ['netflix']
        ]);

        // Create matching media
        $matching = Media::factory()->create([
            'type' => 'tv',
            'rating' => 9.0,
            'providers' => ['netflix'],
            'popularity' => 100
        ]);

        // Create non-matching media (wrong type)
        $wrongType = Media::factory()->create([
            'type' => 'movie',
            'rating' => 9.0,
            'providers' => ['netflix'],
            'popularity' => 90
        ]);

        // Create non-matching media (low rating)
        $lowRating = Media::factory()->create([
            'type' => 'tv',
            'rating' => 5.0,
            'providers' => ['netflix'],
            'popularity' => 80
        ]);

        // Create non-matching media (wrong provider)
        $wrongProvider = Media::factory()->create([
            'type' => 'tv',
            'rating' => 9.0,
            'providers' => ['hulu'],
            'popularity' => 70
        ]);

        $response = $this->actingAs($user)->getJson('/api/swipe');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $matching->id]);
        $response->assertJsonMissing(['id' => $wrongType->id]);
        $response->assertJsonMissing(['id' => $lowRating->id]);
        $response->assertJsonMissing(['id' => $wrongProvider->id]);
    }
}
