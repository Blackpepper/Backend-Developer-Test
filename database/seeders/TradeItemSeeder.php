<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // add trade items to database
        DB::table('trade_items')->insert([
            [
                'name'      => 'Oxygen',
                'points'    => 6,
            ],
            [
                'name'      => 'Water',
                'points'    => 4,
            ],
            [
                'name'      => 'Food',
                'points'    => 3,
            ],
            [
                'name'      => 'Medication',
                'points'    => 2,
            ],
            [
                'name'      => 'Clothing',
                'points'    => 1,
            ],
        ]);
    }
}
