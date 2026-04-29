<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Author;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('posts/Index', [
            'posts' => Post::with('author:id,name')->paginate(30)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('posts/Create', [
            'authors' => Author::query()->select(['id', 'name'])->orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'published' => 'boolean',
        ]);

        Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'content' => $validated['description'],
            'author_id' => $validated['author_id'],
            'published' => $validated['published'] ?? false,
        ]);

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['author', 'comments']);

        return Inertia::render('posts/View', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('posts/Edit', [
            'post' => $post,
            'authors' => Author::query()->select(['id', 'name'])->orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'published' => 'boolean',
        ]);

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'content' => $validated['description'],
            'author_id' => $validated['author_id'],
            'published' => $validated['published'] ?? false,
        ]);

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index');
    }
}
