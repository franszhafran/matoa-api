<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        try {
            $user = User::create([
                "name" => "Zhafran",
                "email" => "frans.zhafran@gmail.com",
                "password" => "empty"
            ]);

            Product::create([
                'name' => 'Jam Matoa',
                'description' => 'Jam keren berbahan kulit',
                'price' => 5000000,
                'stock' => 1,
                'photos' => [],
            ]);

            $cart = Cart::create([
                "products_id" => [1]
            ]);

            $carts = Cart::all();
            $cart = $carts[0];
            print_r($cart->products());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->assertTrue(true);
    }
}
