<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $casts = [
        "products_id" => "array",
    ];

    protected $guarded = [];

    public function products() {
        return Product::whereIn('id', $this->products_id)->get();
    }
}
