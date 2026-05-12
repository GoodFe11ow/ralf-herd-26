<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Game::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('genre')) {
            $query->where('genre', $request->string('genre'));
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->string('platform'));
        }

        $query->orderBy('title', 'asc');

        return Inertia::render('games/Index', [
            'games' => $query->get(),
            'filters' => [
                'search' => $request->string('search')->toString(),
                'genre' => $request->string('genre')->toString(),
                'platform' => $request->string('platform')->toString(),
            ],
        ]);
    }

    public function create(): Response {
        return Inertia::render('games/Create');
    }

        public function store(Request $request)
    {
        Game::create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'platform' => ['required', 'string', 'max:255'],
            'release_year' => ['required', 'integer', 'min:1950', 'max:2100'],
            'genre' => ['required', 'string', 'max:255'],
        ]));

        return redirect()->route('games.index');
    }

    public function edit(Game $game): Response {
        return Inertia::render('games/Edit', [
            'game' => $game
        ]);
    }

        public function update(Request $request, Game $game)
    {
        $game->update($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'platform' => ['required', 'string', 'max:255'],
            'release_year' => ['required', 'integer', 'min:1950', 'max:2100'],
            'genre' => ['required', 'string', 'max:255'],
        ]));

        return redirect()->route('games.index');
    }

        public function destroy(Game $game)
    {
        $game->delete();

        return redirect()->route('games.index');
    }

}
