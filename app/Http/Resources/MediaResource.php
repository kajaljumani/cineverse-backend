<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tmdb_id' => $this->tmdb_id,
            'type' => $this->type,
            'title' => $this->title,
            'overview' => $this->overview,
            'poster_path' => $this->poster_path,
            'backdrop_path' => $this->backdrop_path,
            'genres' => $this->genres, // Assuming this is cast to array in model
            'rating' => $this->rating,
            'release_date' => $this->release_date,
            'popularity' => $this->popularity,
            'cast' => $this->cast,
            'certification' => $this->certification,
            'trailer_url' => $this->trailer_key ? "https://www.youtube.com/watch?v={$this->trailer_key}" : null,
            'runtime' => $this->runtime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // User specific fields
            'is_in_watchlist' => (bool) $this->is_in_watchlist,
            'user_interaction_status' => $this->user_interaction_status,
            // Counts
            'comments_count' => $this->comments_count ?? 0,
            'likes_count' => $this->likes_count ?? 0,
        ];
    }
}
