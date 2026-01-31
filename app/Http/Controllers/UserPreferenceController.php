<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content_type' => 'nullable|string|in:movie,tv,any',
            'genres' => 'nullable|array',
            'languages' => 'nullable|array',
            'min_rating' => 'nullable|numeric|min:0|max:10',
            'release_year_start' => 'nullable|integer|min:1900',
            'release_year_end' => 'nullable|integer|min:1900',
        ]);

        $preference = $request->user()->preferences()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json($preference);
    }

    public function show(Request $request)
    {
        $preference = $request->user()->preferences;

        if (!$preference) {
            return response()->json([
                'content_type' => 'any',
                'genres' => [],
                'languages' => [],
                'min_rating' => 0,
            ]);
        }

        return response()->json($preference);
    }
}
