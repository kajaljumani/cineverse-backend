<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ]);

        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'New Name', 'email' => 'new@example.com']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com'
        ]);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/profile');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
