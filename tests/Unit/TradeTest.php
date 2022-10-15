<?php

namespace Tests\Unit;

use App\Domain\Items\Actions\TradeAction;
use App\Domain\Martians\Models\Martian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TradeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_trade_successful()
    {
        // create trader with 1 oxygen
        $trader1 = Martian::factory()->createOne(['can_trade' => true]);
        $trader1->items()->sync(['1' => ['quantity' => 1]]);

        // create trader with 2 food
        $trader2 = Martian::factory()->createOne(['can_trade' => true]);
        $trader2->items()->sync(['3' => ['quantity' => 2]]);

        $action = app()->make(TradeAction::class);
        $action->do([
            'trader_1' => [
                'id' => $trader1->id,
                'items' => [
                    [ 'id' => 1, 'quantity' => 1 ]
                ]
            ],
            'trader_2' => [
                'id' => $trader2->id,
                'items' => [
                    [ 'id' => 3, 'quantity' => 2 ]
                ]
            ]
        ]);

        $this->assertEquals($trader1->items->first()->id, 3);
        $this->assertEquals($trader1->items->first()->pivot->quantity, 2);
        $this->assertEquals($trader2->items->first()->id, 1);
        $this->assertEquals($trader2->items->first()->pivot->quantity, 1);
    }
}
