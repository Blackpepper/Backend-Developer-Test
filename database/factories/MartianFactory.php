<?php

namespace Database\Factories;

use App\Domain\Martians\Models\Martian;
use Faker\Provider\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class MartianFactory extends Factory
{
    protected $model = Martian::class;

    public function definition()
    {
        $gender = Arr::random(['Male', 'Female', 'Non-binary']);

        $fakerGender = [
            'Male' => Person::GENDER_MALE,
            'Female' => Person::GENDER_FEMALE,
            'Non-binary' => Arr::random([Person::GENDER_MALE, Person::GENDER_FEMALE])
        ];

        return [
            'name' => $this->faker->name($fakerGender[$gender]),
            'gender' => $gender,
            'age' => mt_rand(20, 60),
            'can_trade' => Arr::random([true, true, true, false])
        ];
    }
}
