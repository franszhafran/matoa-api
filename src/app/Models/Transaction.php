<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $casts = [
        "shipment_data" => "object",
        "order_data" => "array",
        "created_at" => 'datetime:j F Y',
    ];

    protected $guarded = [];
}
