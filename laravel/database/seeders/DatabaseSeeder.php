<?php

namespace Database\Seeders;

use App\Models\Martian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //refresh data
        DB::table('martians')->truncate();
        DB::table('products')->truncate();
        DB::table('inventories')->truncate();

        Martian::factory(2)->create();

        $this->call([
            ProductSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
