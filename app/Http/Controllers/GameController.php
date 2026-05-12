<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('games/Index', [
            'games' => Game::query()->latest()->get(),
        ]);
    }
}
