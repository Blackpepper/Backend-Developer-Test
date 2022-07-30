<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MartianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'age' => rand(20, 60),
            'gender' => Arr::random(['M', 'F']),
            'can_trade' => Arr::random([true, false]),
        ];
    }
}
