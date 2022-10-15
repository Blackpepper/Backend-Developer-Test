<?php

namespace Tests\Feature;

use App\Domain\Items\Models\Item;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function test_list_items()
    {
        $totalItemsCount = Item::count();

        $this->json('GET', '/api/items')
            ->assertSuccessful()
            ->assertJsonCount($totalItemsCount, 'data');

        $this->json('GET', '/api/items', ['page' => 1, 'limit' => 5])
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    }

    public function test_store_an_item_error_when_invalid_input()
    {
        $data = [
            'name' => 1,
            'points' => 'string'
        ];

        $this->json('POST', '/api/items', $data)
            ->assertStatus(422);

        $data = [
            'name' => 'Knife',
        ];

        $this->json('POST', '/api/items', $data)
            ->assertStatus(422);
    }

    public function test_store_an_item()
    {
        $data = [
            'name' => 'Knife',
            'points' => 10
        ];

        $this->json('POST', '/api/items', $data)
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $data['name']
            ]);
    }

    public function test_update_an_item_error_when_invalid_input()
    {
        $item = Item::create([
            'name' => 'Knife',
            'points' => 10
        ]);

        $data = [
            'points' => 'string'
        ];

        $this->json('PUT', "/api/items/$item->id", $data)
            ->assertStatus(422);
    }

    public function test_update_an_item()
    {
        $item = Item::create([
            'name' => 'Knife',
            'points' => 10
        ]);

        $data = [
            'points' => 12
        ];

        $this->json('PUT', "/api/items/$item->id", $data)
            ->assertStatus(200)
            ->assertJsonFragment([
                'points' => $data['points']
            ]);
    }
}
