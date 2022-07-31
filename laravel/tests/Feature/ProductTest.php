<?php

namespace Tests\Feature;

use App\Models\Martian;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->post('/api/products',[
            "name" => "Mystery box",
            "points" => 10,
        ]);

        $response->assertStatus(201);
    }

    public function test_update()
    {
        $product = Product::create([
            "name" => "Mystery box",
            "points" => 10,
        ]);

        $response = $this->put("/api/products/{$product->id}",[
            "name" => "Mystery box edited",
            "points" => 12,
        ]);

        $response->assertStatus(200);
    }

    public function test_get_details()
    {
        $product = Product::create([
            "name" => "Mystery box",
            "points" => 10,
        ]);
        $response = $this->get("/api/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                 ->has('data')
        );
    }
}
