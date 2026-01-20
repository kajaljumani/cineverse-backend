<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Media;
use App\Enums\MediaType;

class TMDBService
{
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function fetchAndCacheAll()
    {
        $this->fetchTrending('movie');
        $this->fetchTrending('tv');
        $this->fetchPopular('movie');
        $this->fetchPopular('tv');
    }

    public function fetchTrending($type = 'all', $timeWindow = 'day')
    {
        $response = Http::get("{$this->baseUrl}/trending/{$type}/{$timeWindow}", [
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

    public function fetchPopular($type = 'movie')
    {
        $endpoint = $type === 'movie' ? 'movie/popular' : 'tv/popular';
        
        $response = Http::get("{$this->baseUrl}/{$endpoint}", [
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $results = $response->json()['results'];
            foreach ($results as $item) {
                // Ensure media_type is set for specific endpoints
                $item['media_type'] = $type;
                $this->cacheMedia($item);
            }
            return $results;
        }

        return [];
    }

    /**
     * Import data dynamically when local feed is exhausted.
     */
    public function importFromTMDB($user = null)
    {
        // Strategy 1: User Preferences
        if ($user && $user->preferences) {
            $this->discoverByPreferences($user->preferences);
        }

        // Strategy 2: Recent Years (Fallback)
        // Fetches from current year downwards to ensure fresh content
        $currentYear = (int) date('Y');
        
        // Try current year and previous year randomly to fill gaps
        $years = [$currentYear, $currentYear - 1, $currentYear - 2];
        $selectedYear = $years[array_rand($years)];
        
        // Random page 1-5 to get depth
        $page = rand(1, 5);

        // Fetch Movies
        $this->discover('movie', [
             'primary_release_year' => $selectedYear,
             'sort_by' => 'popularity.desc',
             'page' => $page
        ]);
        
        // Fetch TV
        $this->discover('tv', [
             'first_air_date_year' => $selectedYear,
             'sort_by' => 'popularity.desc',
             'page' => $page
        ]);
    }

    public function discoverByPreferences($prefs)
    {
        $page = rand(1, 5); // Add randomness
        
        // Common Params
        $params = [
            'sort_by' => 'popularity.desc',
            'page' => $page, 
            'vote_average.gte' => $prefs->min_rating ?? 0,
        ];

        if (!empty($prefs->genres)) {
             $params['with_genres'] = implode(',', $prefs->genres);
        }

        // --- Movies ---
        $movieParams = $params;
        if ($prefs->release_year_start) {
            $movieParams['primary_release_date.gte'] = "{$prefs->release_year_start}-01-01";
        }
        if ($prefs->release_year_end) {
            $movieParams['primary_release_date.lte'] = "{$prefs->release_year_end}-12-31";
        }
        $this->discover('movie', $movieParams);

        // --- TV Shows ---
        $tvParams = $params;
        if ($prefs->release_year_start) {
            $tvParams['first_air_date.gte'] = "{$prefs->release_year_start}-01-01";
        }
        if ($prefs->release_year_end) {
            $tvParams['first_air_date.lte'] = "{$prefs->release_year_end}-12-31";
        }
        $this->discover('tv', $tvParams);
    }

    public function discover($type = 'movie', $params = [])
    {
        $endpoint = $type === 'movie' ? 'discover/movie' : 'discover/tv';
        
        $response = Http::get("{$this->baseUrl}/{$endpoint}", array_merge([
            'api_key' => $this->apiKey,
        ], $params));

        if ($response->successful()) {
            $results = $response->json()['results'] ?? [];
            foreach ($results as $item) {
                $item['media_type'] = $type; 
                $this->cacheMedia($item);
            }
            return $results;
        }

        return [];
    }

    public function fetchDetails(Media $media)
    {
        $endpoint = ($media->type === MediaType::Movie) ? "movie/{$media->tmdb_id}" : "tv/{$media->tmdb_id}";
        
        $response = Http::get("{$this->baseUrl}/{$endpoint}", [
            'api_key' => $this->apiKey,
            'append_to_response' => 'credits,videos,release_dates,content_ratings',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->updateMediaDetails($media, $data);
            return true;
        }

        return false;
    }

    public function getVideos(Media $media)
    {
        $endpoint = ($media->type === MediaType::Movie) ? "movie/{$media->tmdb_id}/videos" : "tv/{$media->tmdb_id}/videos";

        $response = Http::get("{$this->baseUrl}/{$endpoint}", [
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $results = $response->json()['results'] ?? [];
            return collect($results)->map(function ($video) {
                return [
                    'id' => $video['id'],
                    'key' => $video['key'],
                    'name' => $video['name'],
                    'type' => $video['type'],
                    'site' => $video['site'],
                ];
            })->toArray();
        }

        return [];
    }


    protected function updateMediaDetails(Media $media, $data)
    {
        // Cast (Top 5)
        $cast = collect($data['credits']['cast'] ?? [])
            ->take(5)
            ->map(fn($actor) => [
                'name' => $actor['name'],
                'character' => $actor['character'],
                'profile_path' => $actor['profile_path'],
            ])
            ->values()
            ->toArray();

        // Certification
        $certification = null;
        if ($media->type === MediaType::Movie) {
            $releaseDates = $data['release_dates']['results'] ?? [];
            foreach ($releaseDates as $release) {
                if ($release['iso_3166_1'] === 'US') {
                    foreach ($release['release_dates'] as $date) {
                        if (!empty($date['certification'])) {
                            $certification = $date['certification'];
                            break 2;
                        }
                    }
                }
            }
        } else {
            $ratings = $data['content_ratings']['results'] ?? [];
            foreach ($ratings as $rating) {
                if ($rating['iso_3166_1'] === 'US') {
                    $certification = $rating['rating'];
                    break;
                }
            }
        }

        // Trailer
        $trailerKey = null;
        $videos = $data['videos']['results'] ?? [];
        foreach ($videos as $video) {
            if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                $trailerKey = $video['key'];
                break;
            }
        }

        // Runtime
        $runtime = $data['runtime'] ?? ($data['episode_run_time'][0] ?? null);

        $media->update([
            'cast' => $cast,
            'certification' => $certification,
            'trailer_key' => $trailerKey,
            'runtime' => $runtime,
        ]);
    }

    protected function cacheMedia($data)
    {
        if (!isset($data['id']) || (!isset($data['title']) && !isset($data['name']))) {
            return;
        }

        $typeStr = $data['media_type'] ?? 'movie';
        if ($typeStr == 'person') return;

        // Map to Enum
        $type = ($typeStr === 'tv') ? MediaType::Tv : MediaType::Movie;

        Media::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'type' => $type,
                'title' => $data['title'] ?? $data['name'],
                'overview' => $data['overview'] ?? null,
                'poster_path' => $data['poster_path'] ?? null,
                'backdrop_path' => $data['backdrop_path'] ?? null,
                'genres' => $data['genre_ids'] ?? [],
                'rating' => $data['vote_average'] ?? 0,
                'release_date' => $data['release_date'] ?? $data['first_air_date'] ?? null,
                'popularity' => $data['popularity'] ?? 0,
            ]
        );
    }
}
