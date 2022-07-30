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
        DB::table('inventories')->truncate();

        Martian::factory(10)->create();

        $this->call([
            InventorySeeder::class,
        ]);
    }
}
