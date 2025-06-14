<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        if ($request->expectsJson() || $request->wantsJson()) {
            $comment->load('user');
            $comment->created_at_human = $comment->created_at->diffForHumans(null, true);
            $comment->created_at_human = str_replace([
                ' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week', ' months', ' month', ' years', ' year'
            ], [
                's', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w', 'mo', 'mo', 'y', 'y'
            ], $comment->created_at_human) . ' ago';
            return response()->json(['success' => true, 'comment' => $comment]);
        }

        return redirect()->back()->with('success', 'Comment added!');
    }

    public function destroy($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        $user = auth()->user();
        // Hanya pemilik komentar atau pemilik post yang boleh hapus
        if ($comment->user_id !== $user->id && $comment->post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        $comment->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Comment deleted!');
    }

    public function index(Post $post)
    {
        $userId = auth()->id();
        $comments = $post->comments()->with('user')->latest()->get()->map(function($comment) use ($userId) {
            $human = $comment->created_at->diffForHumans(null, true);
            $human = str_replace([
                ' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week', ' months', ' month', ' years', ' year'
            ], [
                's', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w', 'mo', 'mo', 'y', 'y'
            ], $human);
            $comment->created_at_human = $human . ' ago';
            $comment->can_delete = $comment->user_id == $userId;
            return $comment;
        });
        return response()->json(['comments' => $comments]);
    }
}