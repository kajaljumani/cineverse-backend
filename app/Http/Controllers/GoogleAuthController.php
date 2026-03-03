<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Google\Client as GoogleClient;

class GoogleAuthController extends Controller
{
    /**
     * Handle Google Login / Registration
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        $idToken = $request->id_token;
        
        try {
            $client = new GoogleClient(['client_id' => config('services.google.client_id')]);
            $payload = $client->verifyIdToken($idToken);

            if (!$payload) {
                return response()->json(['message' => 'Invalid Google ID Token'], 401);
            }

            $email = $payload['email'];
            $name = $payload['name'] ?? 'Google User';
            $googleId = $payload['sub']; // Unique Google ID

            // Find or create user
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Create new user if not exists
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(\Illuminate\Support\Str::random(16)), // Random password for social login
                    'google_id' => $googleId, // Assuming you might want to store this
                ]);
            } else {
                // Update Google ID if not set
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleId]);
                }
            }

            // Create Sanctum Token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error during Google Login'], 500);
        }
    }
}
