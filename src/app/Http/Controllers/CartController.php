<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\AWSFileStorageService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private AWSFileStorageService $AWSFileStorageService
    ) {}

    public function detail(Request $request) {
        $cart = Cart::where('id', $request->user->id)->first();

        if($cart instanceof Cart) {
            return $this->sendData(new CartResource($cart));
        } else {
            return $this->sendError('unknown', 'failed', 400);
        }
    }

    public function addToCart(Request $request) {
        try {
            $cart = Cart::where('id', $request->user->id)->firstOrFail();

            if($cart instanceof Cart) {
                try {
                    $cart->products_id[] = $request->product_id;
                    $cart->save();
                    return $this->sendOk();
                } catch (\Exception $e) {
                    return $this->handleException($e);
                }
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
