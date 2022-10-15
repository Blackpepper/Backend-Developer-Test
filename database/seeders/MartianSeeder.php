<?php

namespace Database\Seeders;

use App\Domain\Items\Models\Item;
use App\Domain\Martians\Models\Martian;
use Illuminate\Database\Seeder;

class MartianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $martian = Martian::create([
            'name' => 'Juan Dela Cruz',
            'age' => 20,
            'gender' => 'Male',
            'can_trade' => true
        ]);
        $martian->items()->sync([1 => ['quantity' => 1]]);

        $martian = Martian::create([
            'name' => 'Pedro Dela Cruz',
            'age' => 20,
            'gender' => 'Male',
            'can_trade' => true
        ]);
        $martian->items()->sync([3 => ['quantity' => 2]]);

        $items = Item::get();
        Martian::factory(8)->create()
            ->each(function (Martian $martian) use ($items) {
                $martian->items()->sync([$items->random()->id => ['quantity' => mt_rand(5, 20)]]);
            });
    }
}
