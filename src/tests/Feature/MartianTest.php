<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MartianTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_martian()
    {
        $response = $this->json(
            'POST',
            $this->url,
            []
        );

        $response->assertStatus(201);
    }
}
