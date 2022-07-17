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
}
