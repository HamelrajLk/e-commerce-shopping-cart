<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function store()
    {
        $user = auth()->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return back();
        }

        $total = $cart->items->sum(fn ($item) =>
            $item->product->price * $item->quantity
        );

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $cart->items()->delete();

        return redirect()->route('products');
    }
}
