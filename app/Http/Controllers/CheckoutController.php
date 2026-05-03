<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;


class CheckoutController extends Controller
{
    public function index(Request $request): Response
    {
        $cart = array_values($request->session()->get('cart', []));

        $total = collect($cart)->sum(function ($item) {
            return $item['quantity'] * (float) $item['price'];
        });

        return Inertia::render('shop/Checkout', [
            'cart' => $cart,
            'total' => number_format($total, 2, '.', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:stripe'],
        ]);

        $cart = array_values($request->session()->get('cart', []));

        if (count($cart) === 0) {
            return redirect()->route('cart.index');
        }

        $total = collect($cart)->sum(function ($item) {
            return $item['quantity'] * (float) $item['price'];
        });

        $order = DB::transaction(function () use ($validated, $cart, $total) {
            $order = Order::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'total' => $total,
                'status' => 'pending',
                'payment_provider' => $validated['payment_method'],
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order;
        });

        return redirect()->route('checkout.pay', $order);
    }

        public function pay(Order $order)
    {
        $order->load('items.product');

        Stripe::setApiKey((string) config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode' => 'payment',
            'success_url' => route('checkout.success', $order),
            'cancel_url' => route('checkout.cancel', $order),
            'customer_email' => $order->email,
            'line_items' => $order->items->map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->product->name,
                        ],
                        'unit_amount' => (int) round(((float) $item->price) * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            })->values()->all(),
        ]);

        $order->update([
            'payment_provider' => 'stripe',
            'payment_reference' => $session->id,
        ]);

        return Inertia::location($session->url);
    }

    public function success(Request $request, Order $order): RedirectResponse
    {
        $order->update([
            'status' => 'paid',
        ]);

        $request->session()->forget('cart');

        return redirect()->route('shop.index')->with('success', 'Payment successful.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled.');
    }


}
