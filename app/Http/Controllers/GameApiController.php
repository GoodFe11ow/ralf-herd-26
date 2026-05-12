<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();
        $genre = $request->string('genre')->toString();
        $platform = $request->string('platform')->toString();
        $sort = $request->string('sort')->toString();
        $direction = $request->string('direction')->toString();
        $limit = (int) $request->integer('limit', 50);

        if (! in_array($sort, ['title', 'release_year'], true)) {
            $sort = 'title';
        }

        if (strtolower($direction) !== 'desc') {
            $direction = 'asc';
        }

        if ($limit < 1) {
            $limit = 1;
        }

        if ($limit > 100) {
            $limit = 100;
        }

        $cacheKey = 'games_api:' . md5(json_encode([
            'search' => $search,
            'genre' => $genre,
            'platform' => $platform,
            'sort' => $sort,
            'direction' => $direction,
            'limit' => $limit,
        ]));

        $games = Cache::remember($cacheKey, now()->addHour(), function () use (
            $search,
            $genre,
            $platform,
            $sort,
            $direction,
            $limit
        ) {
            $query = Game::query();

            if ($search !== '') {
                $query->where('title', 'like', '%' . $search . '%');
            }

            if ($genre !== '') {
                $query->where('genre', $genre);
            }

            if ($platform !== '') {
                $query->where('platform', $platform);
            }

            return $query
                ->orderBy($sort, $direction)
                ->limit($limit)
                ->get();
        });

        return response()->json($games);
    }

}
