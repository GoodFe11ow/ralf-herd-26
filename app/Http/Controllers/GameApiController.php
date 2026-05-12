<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::query();

        if($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->string('search') . '%');
        }

        if($request->filled('genre')) {
            $query->where('genre', $request->string('genre'));
        }

        if($request->filled('platform')) {
            $query->where('platform', $request->string('platform'));
        }

        $allowedSorts = ['title', 'release_year'];
        $sort = $request->string('sort')->toString();

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'title';
        }

        $direction = strtolower($request->string('direction')->toString()) === 'desc'
            ? 'desc'
            : 'asc';

        $query->orderBy($sort, $direction);

        $limit = (int) $request->integer('limit', 50);

        if($limit < 1) {
            $limit = 1;
        }

        if ($limit > 100) {
            $limit = 100;
        }

        return response()->json(
            $query->limit($limit)->get()
        );
    }
}
