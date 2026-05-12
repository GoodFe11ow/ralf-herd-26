<?php

use App\Http\Controllers\GameApiController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameApiController::class, 'index']);

Route::get('/games-docs', function () {
    return response()->json([
        'endpoint' => '/api/games',
        'supported_query_parameters' => [
            'search' => 'Search by game title',
            'genre' => 'Filter by genre',
            'platform' => 'Filter by platform',
            'sort' => 'Sort by title or release_year',
            'direction' => 'asc or desc',
            'limit' => 'Limit number of returned records',
        ],
        'examples' => [
            '/api/games',
            '/api/games?search=witcher',
            '/api/games?genre=RPG',
            '/api/games?platform=PC',
            '/api/games?sort=release_year&direction=desc',
            '/api/games?limit=5',
            '/api/games?search=elden&genre=RPG&platform=PC&sort=title&direction=asc&limit=10',
        ],
        'response_shape' => [
            [
                'id' => 1,
                'title' => 'Example Game',
                'image' => 'https://example.com/image.jpg',
                'description' => 'Example description',
                'platform' => 'PC',
                'release_year' => 2022,
                'genre' => 'RPG',
                'created_at' => '2026-05-12T12:00:00.000000Z',
                'updated_at' => '2026-05-12T12:00:00.000000Z',
            ],
        ],
        'cache' => 'API responses are cached for 1 hour based on query parameters.',
    ]);
});
