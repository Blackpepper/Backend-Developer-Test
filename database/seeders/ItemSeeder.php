<?php

namespace Database\Seeders;

use App\Domain\Items\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = [
            'Oxygen' => 6,
            'Water' => 4,
            'Food' => 3,
            'Medication' => 2,
            'Clothing' => 1
        ];

        foreach ($defaults as $k => $v) {
            Item::updateOrCreate([
                'name' => $k,
            ], [
                'points' => $v
            ]);
        }
    }
}
