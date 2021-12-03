<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AWSFileStorageService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private AWSFileStorageService $AWSFileStorageService
    ) {}

    public function list() {
        $products = Product::orderBy('created_at', 'desc')->get();
        
        return $this->sendData($products);
    }
    
    public function detail(int $id) {
        $product = Product::where('id', $id)->first();
        
        return $this->sendData($product);
    }

    public function store(Request $request) {
        try {
            $photo = $request->file("photo");

            $filename = md5($request->file("photo")->getClientOriginalName() . "se3d");

            $photoUrl = $this->AWSFileStorageService->save(file_get_contents($photo), $filename);

            $photoUrl = $this->AWSFileStorageService->getUrl($filename);

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'photos' => [$photoUrl],
            ]);

            return $this->sendOk();
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
