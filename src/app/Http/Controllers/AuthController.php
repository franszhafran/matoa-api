<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\AWSFileStorageService;
use App\Services\JWTService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private JWTService $JWTService
    ) {}

    public function login(Request $request) {
        
    }

    public function register() {
        
    }
}
