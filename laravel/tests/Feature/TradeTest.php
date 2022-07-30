<?php

namespace Tests\Feature;

use App\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TradeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->post('/api/martians',[
            "name" => "Juan Lansangan",
            "age" => 34,
            "gender" => "M"
        ]);

        $response->assertStatus(201);
    }
}