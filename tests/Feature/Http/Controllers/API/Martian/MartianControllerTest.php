<?php

namespace Tests\Feature\Http\Controllers\API\Martian;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MartianControllerTest extends TestCase
{
    /**
     * Martians index test
     *
     * @return void
     */
    public function test_martians_index_return_a_successful_response()
    {
        $response = $this->get('/martians');
        $response->assertStatus(200);
    }

}
