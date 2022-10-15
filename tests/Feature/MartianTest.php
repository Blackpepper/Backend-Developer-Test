<?php

namespace Tests\Feature;

use App\Domain\Martians\Models\Martian;
use Tests\TestCase;

class MartianTest extends TestCase
{
    public function test_list_martians()
    {
        $totalItemsCount = Martian::count();

        $this->json('GET', '/api/martians')
            ->assertSuccessful()
            ->assertJsonCount($totalItemsCount, 'data');

        $this->json('GET', '/api/martians', ['page' => 1, 'limit' => 2])
            ->assertSuccessful()
            ->assertJsonCount(2, 'data');
    }

    public function test_store_a_martian_error_when_invalid_input()
    {
        $data = [
            'name' => 1,
            'age' => 'string',
            'gender' => 'Other',
            'items' => []
        ];

        $this->json('POST', '/api/martians', $data)
            ->assertStatus(422);

        $data = [
            'name' => 'Juan Dela Cruz',
            'age' => 20
        ];

        $this->json('POST', '/api/martians', $data)
            ->assertStatus(422);
    }

    public function test_store_a_martian()
    {
        $data = [
            'name' => 'Juan Dela Cruz',
            'age' => 20,
            'gender' => 'Male',
            'can_trade' => true,
            'items' => [
                ['id' => 3, 'quantity' => 4]
            ]
        ];

        $response = $this->json('POST', '/api/martians', $data)
            ->assertStatus(201);

        $data = collect($data)->forget('items')->all();
        foreach ($data as $k => $v) {
            $response->assertJsonPath("data.$k", $v);
        }
    }

    public function test_update_a_martian_error_when_invalid_input()
    {
        $martian = Martian::create([
            'name' => 'Juan Dela Cruz',
            'age' => 20,
            'gender' => 'Male',
            'can_trade' => true,
        ]);

        $data = [
            'name' => 1,
            'age' => 'string',
            'gender' => 'Other'
        ];

        $this->json('PUT', "/api/martians/$martian->id", $data)
            ->assertStatus(422);
    }

    public function test_update_a_martian()
    {
        $martian = Martian::create([
            'name' => 'Juan Dela Cruz',
            'age' => 20,
            'gender' => 'Male',
            'can_trade' => true,
        ]);

        $data = [
            'name' => 'Juana Dela Cruz',
            'age' => 25,
            'gender' => 'Female',
            'can_trade' => false
        ];

        $response = $this->json('PUT', "/api/martians/$martian->id", $data)
            ->assertStatus(200);

        foreach ($data as $k => $v) {
            $response->assertJsonPath("data.$k", $v);
        }
    }

    public function test_error_when_update_a_flagged_martian_inventory()
    {
        $martian = Martian::first();
        $martian->update(['can_trade' => false]);

        $data = [
            'items' => [
                ['id' => 1, 'quantity' => 5]
            ]
        ];

        $response = $this->json('PUT', "/api/martians/$martian->id", $data)
            ->assertStatus(409);

        $this->assertStringContainsString(
            'Flagged martians cannot update their inventory.',
            $response->content()
        );
    }

    public function test_update_a_martian_inventory()
    {
        $martian = Martian::first();
        $martian->update(['can_trade' => true]);

        $data = [
            'items' => [
                ['id' => 1, 'quantity' => 5]
            ]
        ];

        $this->json('PUT', "/api/martians/$martian->id", $data)
            ->assertStatus(200);

        $this->assertEquals($martian->items->whereIn('id', 1)->first()->pivot->quantity, 5);
    }
}
