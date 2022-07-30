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

        $martians = Martian::all();
        foreach ($martians as $martian) {
            foreach ($items as $item) {
                $item['martian_id'] = $martian->id;
                Inventory::create($item);
            }
        }
    }
}
