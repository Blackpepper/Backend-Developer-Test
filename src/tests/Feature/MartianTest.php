<?php

namespace Tests\Feature;

use App\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
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

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'age',
                    'gender',
                    'can_trade',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertSee($data->name)
            ->assertSee($data->age)
            ->assertSee($data->gender);
    }

    public function test_it_cannot_create_a_martian()
    {
        $response = $this->json(
            'POST',
            $this->url,
            [
                'name' => 'Faidz'
            ]
        );

        $response->assertStatus(422);
    }

    public function test_it_can_update_a_martian()
    {
        $data = Martian::factory()->create();

        $response = $this->json(
            'PUT',
            $this->url . "/$data->id",
            [
                'name' => 'updated name'
            ]
        );

        $response->assertStatus(200)
            ->assertSee('updated name');
    }

    public function test_it_can_display_martians()
    {
        Martian::factory(10)->create();

        $response = $this->json(
            'GET',
            $this->url
        );

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    public function test_it_can_display_empty_data_for_filtered_martians()
    {
        Martian::factory(5)
            ->state([
                'name' => 'Martians ' . Str::random(6)
            ])
            ->create();

        $response = $this->json(
            'GET',
            $this->url . '?filter=venus'
        );

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_it_can_display_filtered_martians()
    {
        Martian::factory(5)
            ->state([
                'name' => 'Martians ' . Str::random(6)
            ])
            ->create();

        $response = $this->json(
            'GET',
            $this->url . '?filter=martians'
        );

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_it_can_retrieve_a_martian()
    {
        $data = Martian::factory()->create();

        $response = $this->json(
            'GET',
            $this->url . "/$data->id"
        );

        $response->assertStatus(200)
            ->assertSee($data->name)
            ->assertSee($data->age)
            ->assertSee($data->gender);
    }

    public function test_it_cannot_retrieve_a_martian()
    {
        $response = $this->json(
            'GET',
            $this->url . "/1000"
        );

        $response->assertStatus(404);
    }

    public function test_it_can_delete_a_martian()
    {
        $data = Martian::factory()->create();

        $response = $this->json('DELETE', $this->url . "/$data->id");

        $response->assertStatus(204);
    }
}
