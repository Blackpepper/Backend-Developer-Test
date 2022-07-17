<?php

namespace Database\Seeders;

use App\Models\Martian;
use App\Models\Supply;
use App\Support\SupplySupport;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Martian::factory(50)->create()->each(function ($martian) {

            foreach (SupplySupport::allowedSupplies() as $key => $value) {

                Supply::factory()->create(
                    [
                        'martian_id' => $martian->id,
                        'name' => $key,
                        'points' => $value,
                        'status' => 1
                    ]
                );
            }
        });
    }
}
