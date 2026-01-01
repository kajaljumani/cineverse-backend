<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'genres' => 'nullable|array',
            'languages' => 'nullable|array',
            'providers' => 'nullable|array',
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
            return response()->json(['message' => 'Preferences not found'], 404);
        }

        return response()->json($preference);
    }
}
