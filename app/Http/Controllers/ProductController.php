<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('shop/Index', [
            'products' => Product::query()->orderBy('name', 'asc')->get(),
        ]);
    }
}
