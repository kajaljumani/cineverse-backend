<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, $mediaId)
    {
        $comments = \App\Models\Comment::where('media_id', $mediaId)
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }

    public function store(Request $request, $mediaId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $request->user()->comments()->create([
            'media_id' => $mediaId,
            'content' => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }

    public function destroy(Request $request, $id)
    {
        $comment = $request->user()->comments()->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found or unauthorized'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}
