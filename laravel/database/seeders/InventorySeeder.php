<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Martian;
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
        $items = [
            [
                'name' => 'Oxygen',
                'points' => 6,
                'quantity' => 10
            ],
            [
                'name' => 'Water',
                'points' => 4,
                'quantity' => 10
            ],
            [
                'name' => 'Food',
                'points' => 3,
                'quantity' => 10
            ],
            [
                'name' => 'Medication',
                'points' => 2,
                'quantity' => 10
            ],
            [
                'name' => 'Clothing',
                'points' => 1,
                'quantity' => 10
            ],
        ];

        $martians = Martian::all();
        foreach ($martians as $martian) {
            foreach ($items as $item) {
                $item['martian_id'] = $martian->id;
                Inventory::create($item);
            }
        }
    }
}
