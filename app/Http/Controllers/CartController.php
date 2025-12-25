<?php

namespace App\Http\Controllers;

use App\Jobs\LowStockNotificationJob;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_quantity <= 5) {
            LowStockNotificationJob::dispatch($product);
        }

        if ($product->stock_quantity < 1) {
            return back()->withErrors('Product out of stock.');
        }

        $cart = $this->cartService->getOrCreate(auth()->user());

        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            if ($product->stock_quantity < $cartItem->quantity + 1) {
                return back()->withErrors('Not enough stock.');
            }

            $cartItem->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        $product->decrement('stock_quantity');

        return redirect()->route('cart');
    }

    public function index()
    {
        $cart = auth()->user()->cart;

        return Inertia::render('Cart/Index', [
            'cartItems' => $cart
                ? $cart->items()->with('product')->get()
                : [],
        ]);
    }


    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Ensure cart item belongs to authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $product = $cartItem->product;

        $difference = $request->quantity - $cartItem->quantity;

        if ($difference > 0 && $product->stock_quantity < $difference) {
            return back()->withErrors('Not enough stock available.');
        }

        // Update stock
        $product->decrement('stock_quantity', max($difference, 0));
        $product->increment('stock_quantity', max(-$difference, 0));

        // Update quantity
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        return back();
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        // Restore stock
        $cartItem->product->increment('stock_quantity', $cartItem->quantity);

        $cartItem->delete();

        return back();
    }

}
