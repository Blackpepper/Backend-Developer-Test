<?php

namespace Tests\Feature;

use App\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MartianTest extends TestCase
{
    use WithFaker;

    /**
     * test if create martian successfully
     */
    public function testMartianCreatedSuccessfully()
    {
        $martianData = [
            'name' => $this->faker->name,
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Water',
                    'qty' => 5
                ]
            ]
        ];

        $this->json('POST', 'api/martians', $martianData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'age',
                    'gender',
                    'inventory'
                ]
            ]);
    }

    /**
     * test if update martian successfully
     */
    public function testMartianUpdatedSuccessfully()
    {
        $martianData = [
            'name' => $this->faker->name,
            'age' => 5,
            'gender' => 'f',
            'inventory' => [
                [
                    'name' => 'Water',
                    'qty' => 5
                ]
            ]
        ];

        $martian = Martian::create($martianData);

        $payload = $martianData = [
            'name' => $this->faker->name,
            'age' => 5,
            'gender' => 'f',
            'allow_trade' => true
        ];

        $this->json('PUT', 'api/martians/'. $martian->id, $payload, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => $martian->id,
                    'name' => $payload['name'],
                    'age' => $payload['age'],
                    'gender' => $payload['gender'],
                    'allow_trade' => $payload['allow_trade'],
                    'inventory' => [
                        [
                            'name' => 'Water',
                            'qty' => 5
                        ]
                    ]
                ]
            ]);
    }


}
