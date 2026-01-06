<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MediaDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_detail_returns_extended_info()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create([
            'cast' => [['name' => 'Actor 1', 'character' => 'Role 1']],
            'certification' => 'PG-13',
            'trailer_key' => 'xyz123',
            'runtime' => 120,
        ]);

        // Add some comments and likes
        $media->comments()->create(['user_id' => $user->id, 'content' => 'Nice!']);
        $media->interactions()->create(['user_id' => $user->id, 'type' => 'like']);

        $response = $this->actingAs($user)->getJson("/api/media/{$media->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $media->id,
                    'cast' => [['name' => 'Actor 1']],
                    'certification' => 'PG-13',
                    'trailer_url' => 'https://www.youtube.com/watch?v=xyz123',
                    'runtime' => 120,
                    'comments_count' => 1,
                    'likes_count' => 1,
                ]
            ]);
    }

    public function test_media_detail_fetches_from_tmdb_if_missing()
    {
        Http::fake([
            'api.themoviedb.org/*' => Http::response([
                'credits' => ['cast' => [['name' => 'New Actor', 'character' => 'New Role', 'profile_path' => null]]],
                'release_dates' => ['results' => [['iso_3166_1' => 'US', 'release_dates' => [['certification' => 'R']]]]],
                'videos' => ['results' => [['site' => 'YouTube', 'type' => 'Trailer', 'key' => 'newkey']]],
                'runtime' => 90,
            ], 200),
        ]);

        $user = User::factory()->create();
        $media = Media::factory()->create([
            'cast' => null, // Missing details
            'type' => \App\Enums\MediaType::Movie,
        ]);

        $response = $this->actingAs($user)->getJson("/api/media/{$media->id}");

        $response->assertStatus(200);
        
        // Verify DB was updated
        $media->refresh();
        $this->assertNotNull($media->cast);
        $this->assertEquals('R', $media->certification);
        $this->assertEquals('newkey', $media->trailer_key);
        $this->assertEquals(90, $media->runtime);
    }
}
