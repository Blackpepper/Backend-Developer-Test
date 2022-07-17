<?php

namespace Tests\Feature;

use App\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MartianTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/martians';

    public function test_it_can_create_a_martian()
    {
        $data = Martian::factory()->make();

        $response = $this->json(
            'POST',
            $this->url,
            $data->toArray()
        );

        $response->assertStatus(201);
    }
}
