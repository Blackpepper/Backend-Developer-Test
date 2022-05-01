<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MartianTest extends TestCase
{
    use WithFaker;

    /**
     * test if cr eate martian successfully
     */
    public function testMartianCreatedSuccessfully()
    {
        $martianData = [
            'name'      => $this->faker->name,
            'age'       => 5,
            'gender'    => 'm',
            'inventory' => [
                [
                    'name'  => 'Water',
                    'qty'   => 5
                ],
                [
                    'name'  => 'Water',
                    'qty'   => 2
                ]
            ]
        ];

        $this->json('POST','api/martians', $martianData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'age',
                    'gender'
                ]
            ]);
    }









}
