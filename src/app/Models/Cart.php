<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $casts = [
        "products_id" => "array"
    ];

    protected $guarded = [];

    public function products() {
        return Product::whereIn('id', array_keys($this->products_id))->get();
    }
    public function addProduct(int $product_id, int $quantity) {
        $product_ids = $this->products_id;
        if(!array_key_exists($product_id, $product_ids)) {
            $product_ids[$product_id] = $quantity;
        } else {
            $product_ids[$product_id] = $product_ids[$product_id] + $quantity;
        }

        $this->products_id = $product_ids;
    }

    public function setProduct(int $product_id, int $quantity) {
        $product_ids = $this->products_id;
        $product_ids[$product_id] = $quantity;

        $this->products_id = $product_ids;
    }
}
