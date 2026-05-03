<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('shop/Cart', [
            'cart' => array_values($request->session()->get('cart', [])),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $validated['quantity'];
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $validated['quantity'],
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('shop.index');
    }
        public function update(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $validated['quantity'];
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function destroy(Request $request, int $productId): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        unset($cart[$productId]);

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

}
