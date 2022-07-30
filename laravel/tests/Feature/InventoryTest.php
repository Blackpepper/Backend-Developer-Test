<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Martian;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create()
    {
        Martian::factory(1)->create();
        $martian = Martian::first();
        
        Artisan::call('db:seed --class=ProductSeeder');
        $product = Product::first();
        $response = $this->post("/api/martians/inventories",[
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 1
        ]);

        $response->assertStatus(201);
    }

    public function test_update()
    {
        Martian::factory(1)->create();
        $martian = Martian::first();
        Artisan::call('db:seed --class=ProductSeeder');
        $product = Product::first();

        $inventory = Inventory::create([
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 10
        ]);

        $response = $this->put("/api/martians/inventories/{$inventory->id}",[
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_get_details()
    {
        Martian::factory(1)->create();
        $martian = Martian::first();

        Artisan::call('db:seed --class=ProductSeeder');
        $product = Product::first();

        $inventory = Inventory::create([
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 1,
        ]);

        $response = $this->get("/api/martians/{$martian->id}/inventories/{$inventory->id}");

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                 ->has('data')
        );
    }

    public function test_flagged_martian_should_not_create_inventory()
    {
        $martian = Martian::create([
            "name" => "Juan Lansangan",
            "age" => 34,
            "gender" => "M",
            "can_trade" => false
        ]);

        
        Artisan::call('db:seed --class=ProductSeeder');
        $product = Product::first();

        $response = $this->post("/api/martians/inventories",[
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 1,
        ]);

        $response->assertStatus(400);
    }

    public function test_flagged_martian_should_not_update_inventory()
    {
        $martian = Martian::create([
            "name" => "Juan Lansangan",
            "age" => 34,
            "gender" => "M",
            "can_trade" => false
        ]);

        // $martian = Martian::first();
        Artisan::call('db:seed --class=ProductSeeder');
        $product = Product::first();

        $inventory = Inventory::create([
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 1,
        ]);

        

        $response = $this->put("/api/martians/inventories/{$inventory->id}",[
            "martian_id" => $martian->id,
            "product_id" => $product->id,
            "qty" => 10,
        ]);

        $response->assertStatus(400);
    }
}
