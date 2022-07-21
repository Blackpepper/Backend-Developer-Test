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

    public function test_to_show_api_endpoint_get_martian_by_id()
    {
        $response = $this->get('/api/martians/8');

        $response->assertStatus(200);
    }

    public function test_to_store_new_martian_and_inventory_to_database()
    {

        $postData = [
            'name' => 'John',
            'age' => '31',
            'gender' => 'M',
            'allow' => '1',
            'inventory' => [
                [
                    'itemid' => '2',
                    'quantity' => '20',
                ],
                [
                    'itemid' => '3',
                    'quantity' => '20',
                ],
                [
                    'itemid' => '5',
                    'quantity' => '20',
                ],
            ],
        ];

        $dataArr = serialize($postData);

        $response = $this->post('api/martians-create',[
            'data' => $dataArr,
        ]);

        $response->assertStatus(200);
    }

    public function test_to_allow_martian_to_trade_or_not()
    {
        $allowStatus = (new MartianService())->allowedToTrade(1);
        $this->assertEquals(1, $allowStatus);
    }

    public function test_to_trade_martians()
    {

        $postData = [
            'trade' => [
                'buyFrom' => [
                    'martianid' => 1,
                    'items' => [
                        [
                            'itemid' => 2,
                            'quantity' => 2,
                        ],
                        [
                            'itemid' => 5,
                            'quantity' => 1,
                        ],
                    ],
                ],
                'sellTo' => [
                    'martianid' => 2,
                    'items' => [
                        [
                            'itemid' => 3,
                            'quantity' => 3,
                        ],
                    ],
                ],
            ],
        ];

        $dataArr = serialize($postData);

        $response = $this->post('api/martians-trade',[
            'data' => $dataArr,
        ]);

        $response->assertStatus(200);
    }

}
