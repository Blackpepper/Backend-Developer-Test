<?php

namespace Tests\Feature\Http\Controllers\API\Supply;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplyControllerTest extends TestCase
{
    /**
     * Supplies index test
     *
     * @return void
     */
    public function test_supplies_index_return_a_successful_response()
    {
        $response = $this->get('/supplies');

        $response->assertStatus(200);
    }
}
