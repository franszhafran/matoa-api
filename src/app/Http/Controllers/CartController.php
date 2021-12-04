<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\AWSFileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function checkout(Request $request) {
        $cart = Cart::where('id', $request->user->id)->first();

        try {
            DB::beginTransaction();
            $transaction = new Transaction();

            $shipment_data = new \stdClass();
            $order_data = [];

            $shipment_keys = ["address", "province", "city", "district", "zip_code", "phone_number", "shipping"];
            foreach($shipment_keys as $value) {
                $shipment_data->{$value} = $request->{$value};
            }

            $products = $cart->products();
            $total = 0;
            foreach($products as $key=>$value) {
                $product_snapshot = new \stdClass();
                $product_snapshot_keys = ["id", "name", "price"];
                foreach($product_snapshot_keys as $value2) {
                    $product_snapshot->{$value2} = $value->{$value2};
                }
                $quantity = $cart->products_id[$value->id];
                $order_data[] = [
                    "product" => $product_snapshot,
                    "quantity" => $quantity,
                ];
                $total += $product_snapshot->price*$quantity;
            }
            
            $transaction->user_id = $request->user->id;
            $transaction->shipment_data = $shipment_data;
            $transaction->order_data = $order_data;
            $transaction->total = $total;
            $transaction->status = "waiting_for_payment";
            $transaction->payment_proof_link = "";
            $transaction->save();

            $cart->products_id = [];
            $cart->save();
            DB::commit();
            return $this->sendOk();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function addToCart(Request $request) {
        try {
            $cart = Cart::where('id', $request->user->id)->firstOrFail();

            if($cart instanceof Cart) {
                try {
                    $cart->addProduct($request->product_id, $request->quantity);
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

    public function setCart(Request $request) {
        try {
            $cart = Cart::where('id', $request->user->id)->firstOrFail();

            if($cart instanceof Cart) {
                try {
                    $cart->setProduct($request->product_id, $request->quantity);
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
