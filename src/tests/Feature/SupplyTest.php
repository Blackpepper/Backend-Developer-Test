<?php

namespace Tests\Feature;

use App\Models\Martian;
use App\Models\Supply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplyTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/supplies';

    public function test_it_can_create_a_supply()
    {
        $martian = Martian::factory()->create();
        $data = Supply::factory()->state(['name' => 'Food', 'martian_id' => $martian->id])->create();
        $new = Supply::factory()->state(['name' => 'Food', 'martian_id' => $martian->id])->create();

        $response = $this->json(
            'POST',
            $this->url,
            $new->toArray()
        );

        $response->assertStatus(200)
            ->assertSee($data->quantity + $new->quantity);
    }

    public function test_it_cannot_create_a_supply()
    {
        $response = $this->json(
            'POST',
            $this->url,
            [
                'name' => ''
            ]
        );

        $response->assertStatus(422);
    }

    public function test_it_cannot_create_an_existing_supply_but_can_update()
    {
        $martian = Martian::factory()->create();

        Supply::factory()
            ->state(['name' => 'Oxygen', 'quantity' => 3])
            ->create(['martian_id' => $martian->id]);

        $data = Supply::factory()
            ->state(['name' => 'Oxygen', 'quantity' => 3])
            ->create(['martian_id' => $martian->id]);

        $response = $this->json(
            'POST',
            $this->url,
            $data->toArray()
        );

        $count = $response->json()['data'];

        $this->assertEquals(6, $count['quantity']);
    }

    public function test_it_can_update_a_supply()
    {
        $data = Supply::factory()->create();

        $response = $this->json(
            'PUT',
            $this->url . "/$data->id",
            [
                'name' => 'updated supply name'
            ]
        );

        $response->assertStatus(200)
            ->assertSee('updated supply name');
    }

    public function test_it_can_retrieve_a_supply()
    {
        $data = Supply::factory()->create();

        $response = $this->json(
            'GET',
            $this->url . "/$data->id"
        );

        $response->assertStatus(200)
            ->assertSee($data->name)
            ->assertSee($data->points)
            ->assertSee($data->description);
    }
}
