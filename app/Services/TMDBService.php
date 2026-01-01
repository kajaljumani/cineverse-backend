<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Media;

class TMDBService
{
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function fetchTrending($timeWindow = 'day')
    {
        $response = Http::get("{$this->baseUrl}/trending/all/{$timeWindow}", [
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $results = $response->json()['results'];
            foreach ($results as $item) {
                $this->cacheMedia($item);
            }
            return $results;
        }

        return [];
    }

    public function discover($params = [])
    {
        // Implement discovery logic for movies and tv
        // For now, let's just fetch popular movies as a placeholder
        $response = Http::get("{$this->baseUrl}/discover/movie", array_merge([
            'api_key' => $this->apiKey,
            'sort_by' => 'popularity.desc',
        ], $params));

        if ($response->successful()) {
            $results = $response->json()['results'];
            foreach ($results as $item) {
                $item['media_type'] = 'movie'; // Discover endpoint doesn't always return media_type
                $this->cacheMedia($item);
            }
            return $results;
        }

        return [];
    }

    protected function cacheMedia($data)
    {
        if (!isset($data['id']) || !isset($data['title']) && !isset($data['name'])) {
            return;
        }

        $type = $data['media_type'] ?? 'movie';
        if ($type == 'person') return;

        Media::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'type' => $type,
                'title' => $data['title'] ?? $data['name'],
                'poster_path' => $data['poster_path'] ?? null,
                'backdrop_path' => $data['backdrop_path'] ?? null,
                'genres' => $data['genre_ids'] ?? [],
                'rating' => $data['vote_average'] ?? 0,
                'release_date' => $data['release_date'] ?? $data['first_air_date'] ?? null,
                'popularity' => $data['popularity'] ?? 0,
                // Providers would require a separate call usually, skipping for now or need to fetch details
            ]
        );
    }
}
