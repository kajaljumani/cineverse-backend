<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use App\Enums\MediaType;
use App\Services\TMDBService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MediaVideosTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_videos_endpoint_returns_tmdb_data()
    {
        // 1. Setup Data
        $user = User::factory()->create();
        $media = Media::factory()->create([
            'tmdb_id' => 550, // Fight Club
            'type' => MediaType::Movie,
        ]);

        // 2. Mock TMDB Response
        Http::fake([
            'api.themoviedb.org/3/movie/550/videos*' => Http::response([
                'id' => 550,
                'results' => [
                    [
                        'id' => '5e382d1b4ca676001453826d',
                        'iso_639_1' => 'en',
                        'iso_3166_1' => 'US',
                        'key' => '6JnN1DmbqoU',
                        'name' => 'Fight Club - Theatrical Trailer',
                        'site' => 'YouTube',
                        'size' => 1080,
                        'type' => 'Trailer',
                        'official' => false,
                        'published_at' => '2015-02-26T03:19:25.000Z',
                    ],
                    [
                        'id' => '5c92c5c49251416e9c000d68',
                        'iso_639_1' => 'en',
                        'iso_3166_1' => 'US',
                        'key' => 'BdJKm16Co6M',
                        'name' => 'Fight Club - Trailer',
                        'site' => 'YouTube',
                        'size' => 1080,
                        'type' => 'Trailer',
                        'official' => true,
                        'published_at' => '2019-03-21T01:35:00.000Z',
                    ]
                ]
            ], 200),
        ]);

        // 3. Make Request
        $response = $this->actingAs($user)
            ->getJson("/api/media/{$media->id}/videos");

        // 4. Assert Response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'key',
                        'name',
                        'type',
                        'site',
                    ]
                ]
            ])
            ->assertJsonPath('data.0.key', '6JnN1DmbqoU')
            ->assertJsonPath('data.0.type', 'Trailer');
    }
}
