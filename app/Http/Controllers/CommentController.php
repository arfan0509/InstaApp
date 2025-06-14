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

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

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
        return redirect()->back()->with('success', 'Comment deleted!');
    }
}