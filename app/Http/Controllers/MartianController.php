<?php

namespace App\Http\Controllers;

use App\Http\Resources\MartianCollection;
use App\Http\Resources\MartianResource;
use App\Models\Martian;
use App\Models\TradeItem;
use Illuminate\Http\Request;
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
