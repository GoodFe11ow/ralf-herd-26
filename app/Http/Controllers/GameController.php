<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

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

    public function movies(): Response
    {
        $sourceUrl = 'https://raamistikud.ta24armus.itmajakas.ee/api/my-favorite-subjects';

        $payload = Cache::remember('classmate_movies_cards_v2', now()->addMinutes(15), function () use ($sourceUrl) {
            try {
                $response = Http::acceptJson()->timeout(10)->get($sourceUrl);

                if (! $response->successful()) {
                    return [
                        'movies' => [],
                        'error' => 'Could not load the movies API right now.',
                    ];
                }

                $data = $response->json();

                if (is_array($data) && array_is_list($data)) {
                    return [
                        'movies' => $data,
                        'meta' => null,
                        'error' => null,
                    ];
                }

                if (is_array($data) && isset($data['data']) && is_array($data['data'])) {
                    return [
                        'movies' => $data['data'],
                        'meta' => is_array($data['meta'] ?? null) ? $data['meta'] : null,
                        'error' => null,
                    ];
                }
            } catch (\Throwable) {
                return [
                    'movies' => [],
                    'meta' => null,
                    'error' => 'Could not load the movies API right now.',
                ];
            }

            return [
                'movies' => [],
                'meta' => null,
                'error' => 'The movies API returned an unexpected response.',
            ];
        });

        return Inertia::render('games/Movies', [
            'movies' => $payload['movies'],
            'meta' => $payload['meta'] ?? null,
            'error' => $payload['error'],
            'sourceUrl' => $sourceUrl,
        ]);
    }

}
