<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class FirebaseService
{
    protected $projectId;
    protected $credentialsPath;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->credentialsPath = storage_path('app/firebase-auth.json');
    }

    /**
     * Get Google OAuth2 Access Token
     */
    protected function getAccessToken()
    {
        if (!file_exists($this->credentialsPath)) {
            Log::error("Firebase credentials file not found at {$this->credentialsPath}");
            return null;
        }

        try {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $this->credentialsPath);
            $token = $credentials->fetchAuthToken(HttpHandlerFactory::build());
            
            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error("Error fetching Firebase access token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Send Push Notification to a specific token
     */
    public function sendNotification($token, $title, $body, $data = [])
    {
        if (empty($token) || empty($this->projectId)) {
            Log::warning("FCM Send skipped: Token or Project ID is missing.");
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return false;
        }

        // Ensure all data values are strings for FCM v1
        $formattedData = [];
        foreach ($data as $key => $value) {
            $formattedData[(string)$key] = (string)$value;
        }

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $formattedData,
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'default',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        if ($response->successful()) {
            return true;
        }

        Log::error("FCM Send Error for token {$token}: " . $response->body());
        return false;
    }

    /**
     * Send Broadcast Notification to all users with tokens
     */
    public function broadcast($title, $body, $data = [])
    {
        $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token');
        $successCount = 0;

        foreach ($tokens as $token) {
            if ($this->sendNotification($token, $title, $body, $data)) {
                $successCount++;
            }
        }

        return $successCount;
    }
}
