<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedCountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_includes_correct_counts()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['popularity' => 1000]); // Ensure high popularity for trending

        // Add 2 comments
        $media->comments()->create(['user_id' => $user->id, 'content' => 'Nice']);
        $media->comments()->create(['user_id' => $user->id, 'content' => 'Cool']);

        // Add 3 likes
        $media->interactions()->create(['user_id' => $user->id, 'type' => 'like']);
        // Add more likes from other users
        $otherUser1 = User::factory()->create();
        $otherUser2 = User::factory()->create();
        $media->interactions()->create(['user_id' => $otherUser1->id, 'type' => 'like']);
        $media->interactions()->create(['user_id' => $otherUser2->id, 'type' => 'like']);
        // Add a dislike (should not count)
        $media->interactions()->create(['user_id' => $otherUser2->id, 'type' => 'dislike']);

        // Test Global Feed
        $response = $this->actingAs($user)->getJson('/api/feed');
        $response->assertStatus(200);

        // Find the media in trending
        $trending = $response->json('trending');
        $found = false;
        foreach ($trending as $item) {
            if ($item['id'] === $media->id) {
                $found = true;
                $this->assertEquals(2, $item['comments_count'], 'Trending: Comments count mismatch');
                $this->assertEquals(3, $item['likes_count'], 'Trending: Likes count mismatch');
                break;
            }
        }
        $this->assertTrue($found, 'Media not found in trending');
    }

    public function test_swipe_feed_includes_correct_counts()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();

        // Add comments and likes
        $media->comments()->create(['user_id' => $user->id, 'content' => 'Nice']);
        $media->interactions()->create(['user_id' => User::factory()->create()->id, 'type' => 'like']);

        $response = $this->actingAs($user)->getJson('/api/swipe');
        $response->assertStatus(200);
        
        $data = $response->json('data');
        $found = false;
        foreach ($data as $item) {
            if ($item['id'] === $media->id) {
                $found = true;
                $this->assertEquals(1, $item['comments_count'], 'Swipe: Comments count mismatch');
                $this->assertEquals(1, $item['likes_count'], 'Swipe: Likes count mismatch');
                break;
            }
        }
        $this->assertTrue($found, 'Media not found in swipe feed');
    }
}
