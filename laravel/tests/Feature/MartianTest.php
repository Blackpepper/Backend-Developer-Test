<?php

namespace Tests\Feature;

use App\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MartianTest extends TestCase
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

    public function test_update()
    {
        Martian::factory(1)->create();
        $martian = Martian::first();
        $response = $this->put("/api/martians/{$martian->id}",[
            "name" => "Juan Lansangan Updated",
            "age" => 34,
            "gender" => "M",
            "can_trade" => false
        ]);

        $response->assertStatus(200);
    }

    public function test_get_details()
    {
        Martian::factory(1)->create();
        $martian = Martian::first();
        $response = $this->get("/api/martians/{$martian->id}");

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                 ->has('data')
        );
    }
}
