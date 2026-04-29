<?php


namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller {
    public function store(Request $request, Post $post) {
        Comment::create([
            'author_name' => $request->user()->name,
            'content' => $request->validate([
                'content'=> ['required', 'string'],
            ])['content'],
            'post_id' => $post->id,
        ]);

        return redirect()->route('posts.show', $post);
    }

    public function destroy(Comment $comment): RedirectResponse {
        if (! request()->user()?-is_admin) {
            abort(403);
        }

        $post = $comment->post;

        $comment->delete();

        return redirect()->route('posts.show', $post);
    }
}