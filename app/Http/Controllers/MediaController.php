<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Services\TMDBService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    protected $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function show(Request $request, $id)
    {
        $media = \App\Models\Media::withUserStatus($request->user())
            ->withCounts()
            ->findOrFail($id);
        
        // Fetch details from TMDB if missing
        if (empty($media->cast) || empty($media->certification)) {
            $this->tmdbService->fetchDetails($media);
            // No need to refresh, update() syncs the model instance
        }

        return new MediaResource($media);
    }
}
