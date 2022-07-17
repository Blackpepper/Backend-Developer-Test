<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'age' => rand(20, 80),
            'can_trade' => rand(0, 1),
        ];
    }
}
