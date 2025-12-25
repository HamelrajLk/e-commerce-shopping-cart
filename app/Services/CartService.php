<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;

class CartService
{
    public function getOrCreate(User $user): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $user->id
        ]);
    }
}
