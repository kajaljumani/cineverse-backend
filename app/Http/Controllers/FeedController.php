<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Services\FeedService;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    protected $feedService;

    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    /**
     * Home Swipe (Personalized)
     * Endpoint: /swipe
     */
    public function swipe(Request $request)
    {
        $feed = $this->feedService->getSwipeFeed($request->user());
        return MediaResource::collection($feed);
    }

    /**
     * Global Feed (Non-personalized)
     * Endpoint: /feed
     */
    public function index(Request $request)
    {
        if ($request->has('type')) {
            $type = $request->input('type', 'trending');
            $search = $request->input('query');
            
            $feed = $this->feedService->getFeed($type, $request->user(), $search);
            return MediaResource::collection($feed);
        }

        $feed = $this->feedService->getGlobalFeed($request->user());
        
        return response()->json([
            'trending' => MediaResource::collection($feed['trending']),
            'latest' => MediaResource::collection($feed['latest']),
            'random' => MediaResource::collection($feed['random']),
        ]);
    }
}
