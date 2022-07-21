<?php

namespace Tests\Feature\Martian;

use App\Services\MartianService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MartianTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_to_show_api_endpoint_martian_list()
    {
        $response = $this->get('/api/martians');

        $response->assertStatus(200);
    }

    public function test_to_show_api_endpoint_price_table()
    {
        $response = $this->get('/api/pricetable');

        $response->assertStatus(200);
    }

}
