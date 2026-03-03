<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // Genre Badges
            ['name' => 'Horror Hero', 'description' => 'Watch 10 Horror movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_horror_hero_mockup_1772366215492.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Horror'],
            ['name' => 'Romance Royal', 'description' => 'Watch 10 Romance movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_romance_royal_1772366240338.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Romance'],
            ['name' => 'Action Ace', 'description' => 'Watch 10 Action movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_action_ace_1772366255838.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Action'],
            ['name' => 'Sci-Fi Scholar', 'description' => 'Watch 10 Sci-Fi movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_scifi_scholar_1772366270620.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Science Fiction'],
            ['name' => 'Comedy Champ', 'description' => 'Watch 10 Comedy movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_comedy_champ_retry_1772366294382.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Comedy'],
            ['name' => 'Drama Duke', 'description' => 'Watch 10 Drama movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_drama_duke_1772366309334.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Drama'],
            ['name' => 'Thriller Titan', 'description' => 'Watch 10 Thriller movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_thriller_titan_1772366327349.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Thriller'],
            ['name' => 'Docu Dean', 'description' => 'Watch 10 Documentaries', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_docu_dean_1772366341433.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Documentary'],
            ['name' => 'Animation Ally', 'description' => 'Watch 10 Animation movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_animation_ally_v4_retry_1772366557723.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Animation'],
            ['name' => 'Mystery Mind', 'description' => 'Watch 10 Mystery movies/series', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_mystery_mind_v4_retry_1772366572494.png', 'criteria_type' => 'genre_watch', 'criteria_value' => 10, 'criteria_detail' => 'Mystery'],

            // Social Badges
            ['name' => 'Social Butterfly', 'description' => 'Start conversations with 5 different users', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_social_butterfly_v2_1772366434930.png', 'criteria_type' => 'social_chat', 'criteria_value' => 5, 'criteria_detail' => 'partners'],
            ['name' => 'Chatterbox', 'description' => 'Send 100 total messages', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_chatterbox_v2_1772366458307.png', 'criteria_type' => 'social_chat', 'criteria_value' => 100, 'criteria_detail' => null],
            ['name' => 'Vocal Critic', 'description' => 'Post 10 comments', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_vocal_critic_v2_retry_1772366588641.png', 'criteria_type' => 'social_comment', 'criteria_value' => 10, 'criteria_detail' => null],
            ['name' => 'Trendsetter', 'description' => 'Gain 10 followers', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_trendsetter_v2_retry_1772366605229.png', 'criteria_type' => 'follower_count', 'criteria_value' => 10, 'criteria_detail' => null],

            // Watching Badges
            ['name' => 'Cinephile', 'description' => 'Add 50 items to your watchlist', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_cinephile_v2_1772366640052.png', 'criteria_type' => 'watchlist_count', 'criteria_value' => 50, 'criteria_detail' => null],
            ['name' => 'Binge Master', 'description' => 'Mark 20 items as "Watched"', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_binge_master_v1_1772366663270.png', 'criteria_type' => 'watch_count', 'criteria_value' => 20, 'criteria_detail' => null],
            ['name' => 'Movie Mogul', 'description' => 'Mark 20 movies as "Watched"', 'icon' => 'http://10.0.2.2:8000/storage/badges/badge_movie_mogul_v1_1772366681998.png', 'criteria_type' => 'watch_count', 'criteria_value' => 20, 'criteria_detail' => 'movie'],
            ['name' => 'Series Specialist', 'description' => 'Mark 20 series as "Watched"', 'icon' => '📺', 'criteria_type' => 'watch_count', 'criteria_value' => 20, 'criteria_detail' => 'tv'],

            // Special
            ['name' => 'Early Bird', 'description' => 'Mark 5 items as "Watched" before 8 AM', 'icon' => '🌅', 'criteria_type' => 'time_sensitive', 'criteria_value' => 5, 'criteria_detail' => 'morning'],
            ['name' => 'Night Owl', 'description' => 'Mark 5 items as "Watched" after 11 PM', 'icon' => '🦉', 'criteria_type' => 'time_sensitive', 'criteria_value' => 5, 'criteria_detail' => 'night'],
            ['name' => 'SwipeScene Legend', 'description' => 'Earn 10 other badges', 'icon' => '🏆', 'criteria_type' => 'badge_count', 'criteria_value' => 10, 'criteria_detail' => null],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
