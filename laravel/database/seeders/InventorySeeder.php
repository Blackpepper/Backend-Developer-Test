<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Martian;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $martians = Martian::all();
        $products = Product::all();
        foreach ($martians as $martian) {
            foreach ($products as $product) {
                Inventory::create([
                    'martian_id' => $martian->id,
                    'product_id' => $product->id,
                    'qty' => 10
                ]);
            }
        }
    }
}
