<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Martian;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Oxygen',
                'points' => 6,
            ],
            [
                'name' => 'Water',
                'points' => 4,
            ],
            [
                'name' => 'Food',
                'points' => 3,
            ],
            [
                'name' => 'Medication',
                'points' => 2,
            ],
            [
                'name' => 'Clothing',
                'points' => 1,
            ],
        ];

        foreach ($items as $item) {
            Product::create($item);
        }
    }
}
