<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Resources\MartianCollection;
use App\Http\Resources\MartianResource;
use App\Models\Martian;
use App\Models\TradeItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MartianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new MartianCollection(Martian::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return MartianResource
     */
    public function store(Request $request)
    {
        try {
            // validate incoming data
            $request->validate([
                'name' => 'required|unique:martians',
                'age' => 'required|integer',
                'gender' => [
                    'required',
                    Rule::in(['f', 'm']),
                ],
                'inventory' => 'required',
                'inventory.*.name' => 'required|exists:trade_items,name',
                'inventory.*.qty' => 'required|integer'
            ]);

            // create new object with accepted params
            $martian = Martian::create($request->only([
                'name',
                'age',
                'gender'
            ]));

            // add inventory to martian
            foreach ($request->get('inventory') as $i) {
                $tradeItem = TradeItem::where('name', $i['name'])->first();

                // check if inventory exist
                $existInventory = DB::table('martian_inventory')->where([
                    'martian_id' => $martian->id,
                    'trade_item_id' => $tradeItem->id
                ])->first();

                if (!$existInventory) {
                    $martian->inventories()->attach($tradeItem->id, [
                        'qty' => $i['qty']
                    ]);
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }

        return new MartianResource($martian);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Martian $martian
     * @return \Illuminate\Http\Response
     */
    public function show(Martian $martian)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Martian $martian
     * @return MartianResource
     */
    public function update(Request $request, Martian $martian)
    {
        try {
            $request->validate([
                'name' => 'unique:martians',
                'age' => 'integer',
                'gender' => [
                    Rule::in(['f', 'm']),
                ],
                'allow_trade' => 'boolean'
            ]);
            $martian->update($request->only([
                'name',
                'age',
                'gender',
                'allow_trade'
            ]));

        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }

        return new MartianResource($martian);
    }


    /**
     * exchange process
     * data provide [
     *      'from_martian_id' => xx,
     *      'from_trade_item_id' => xx,
     *      'trade_items' => [
     *          [
     *              'name' => xxx,
     *              'qty' => xxx
     *          ],
     *          [
     *              'name' => xxx,
     *              'qty' => xxx
     *          ],
     *      ],
     *      'to_martian_id' => xxx,
     *      'to_traade_item_name' => xxx
     * ]
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange(Request $request)
    {
        try {
            // validate incoming data
            $request->validate([
                'from_martian_id' => 'required|integer',
                'from_trade_item_id' => 'required|integer',
                'trade_items' => 'required|array',
                'to_martian_id' => 'required|integer',
                'to_trade_item_name' => 'required',
            ]);

            // validate incoming data

            // -----------------------------------------------------------------------
            // this is validation part for from martian
            // -----------------------------------------------------------------------
            /** @var Martian $fromMartian */
            $fromMartian = Martian::findOrFail($request->get('from_martian_id'));

            // check if from martian allow to exchange
            if (!$fromMartian->allow_trade) {
                throw new \Exception(sprintf('Martian %s does not allow to trade',
                    $fromMartian->name
                ));
            }

            // change incoming trade items array format to Eloquent collection
            $tradeItems = new Collection();
            foreach($request->get('trade_items') as $data) {
                /** @var TradeItem $fromTradeItem */
                $tradeItem = TradeItem::where('name,', $data['name'])->firstOrFail();

                // double check if from martian has specified trade items
                if (!$fromMartian->hasTradeItem($fromTradeItem)) {
                    throw new \Exception(sprintf('%s does not belong to %s',
                        $fromTradeItem->name,
                        $fromMartian->name));
                }

                // double check if from martian has specify correct qty to exchange
                if (!$fromMartian->hasEnoughTradeItem($fromTradeItem, $data['qty'])) {
                    throw new \Exception(sprintf('Martian %s does not have enough %s to trade',
                        $fromMartian->name,
                        $fromTradeItem->name));
                }

                $tradeItem->qty = $data['qty'];

                $tradeItems->add($tradeItem);
            }

            // -----------------------------------------------------------------------
            // this is validation part for to martian
            // -----------------------------------------------------------------------
            /** @var Martian $toMartian */
            $toMartian = Martian::findOrFail($request->get('to_martian_id'));

            // check if to martian allow to exchange
            if (!$toMartian->allow_trade) {
                throw new \Exception(sprintf('Martian %s does not allow to trade',
                    $toMartian->name
                ));
            }

            /** @var TradeItem $toTradeItem */
            $toTradeItem = TradeItem::where('name', $request->get('to_trade_item_name'))->firstOrFail();

            // check to martian own the trade item
            if (!$toMartian->hasTradeItem($toTradeItem)) {
                throw new \Exception(sprintf('%s does not belong to %s',
                    $toTradeItem->name,
                    $toMartian->name));
            }

            // get the required trade item qty from incoming trade items
            $requiredQty = $fromMartian->calculateRequiredQtyOfExchangeTradeItems($tradeItems, $toTradeItem);

            // check if toMartian has enough trade item to exchange
            if (!$toMartian->hasEnoughTradeItem($toTradeItem, $requiredQty)) {
                throw new \Exception(sprintf('%s has not enough %s to exchange'),
                    $toMartian->name, $toTradeItem->name);
            }

            // ------------------------------------------------------------------------
            // everything is ok, can exchange
            // ------------------------------------------------------------------------
            // todo: need to add transaction here to rollback if something happens

            // update from martian inventory
            $fromMartian->updateInventory($tradeItems);

            // update to martian inventory
            $toMartian->updateSingleInventory($toTradeItem, $requiredQty);

            return response()->json([
                'message' => 'Exchange done successfully.'
            ], Response::HTTP_OK);

        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }


    }
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Martian $martian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Martian $martian)
    {
        //
    }
}
