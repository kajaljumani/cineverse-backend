<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Get all conversations for the current user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['messages' => function($query) {
                $query->latest()->limit(1);
            }, 'userOne', 'userTwo'])
            ->get()
            ->map(function ($conversation) use ($user) {
                $otherUser = $conversation->otherUser($user->id);
                $lastMessage = $conversation->messages->first();
                
                return [
                    'id' => $conversation->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'avatar_url' => $otherUser->avatar_url,
                        'is_online' => $otherUser->is_online,
                        'status' => $otherUser->status,
                        'google_id' => $otherUser->google_id,
                    ],
                    'last_message' => $lastMessage ? [
                        'content' => $lastMessage->content,
                        'created_at' => $lastMessage->created_at,
                        'sender_id' => $lastMessage->sender_id,
                    ] : null,
                    'unread_count' => $conversation->messages()
                        ->where('sender_id', '!=', $user->id)
                        ->where('is_read', false)
                        ->count(),
                ];
            })
            // Sort by most recent message
            ->sortByDesc(function($c) {
                return $c['last_message']['created_at'] ?? $c['id'];
            })
            ->values();

        return response()->json($conversations);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function messages(Request $request, $id)
    {
        $user = $request->user();
        $conversation = Conversation::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_one_id', $user->id)
                  ->orWhere('user_two_id', $user->id);
            })->firstOrFail();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a message to a user.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $sender = $request->user();
        $recipientId = $validated['recipient_id'];

        if ($sender->id == $recipientId) {
            return response()->json(['message' => 'You cannot message yourself'], 400);
        }

        // Find or create conversation
        $userOneId = min($sender->id, $recipientId);
        $userTwoId = max($sender->id, $recipientId);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $sender->id,
            'content' => $validated['content'],
        ]);

        // Award badges
        $newlyUnlocked = app(\App\Services\BadgeService::class)->checkAndAward($sender, 'social_chat'); // partners/total

        return response()->json([
            'message' => $message->load('sender'),
            'newly_unlocked' => $newlyUnlocked
        ], 201);
    }

    /**
     * Get 20 recently online users.
     */
    public function buddies(Request $request)
    {
        $user = $request->user();
        $buddies = User::where('id', '!=', $user->id)
            ->orderBy('last_online_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($buddies);
    }
}
