<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Martian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'martian_id' => 'required',
            'items' => 'required|array',
            'items.*.item_id' => 'required|numeric',
            'items.*.qty' => 'required|numeric',
            'right_trader' => 'required',
            'right_trader.martian_id' => 'required',
            'right_trader.items' => 'required|array',
            'right_trader.items.*.item_id' => 'required|numeric',
            'right_trader.items.*.qty' => 'required|numeric',
        ]);

        
        $rightTrader = $request->right_trader;
        $leftTraderId = $request->martian_id;
        
        if($leftTraderId === $rightTrader['martian_id']) {
            return response()->json([
                'message' => 'Martian should not trade on itself',
            ], 400);
        }

        // check if right trader exist
        $rightTraderExist = Martian::find($rightTrader['martian_id']);
        if(!$rightTraderExist) {
            return response()->json([
                'message' => 'Martian right trader not exist',
            ], 404);
        }

        // check if items of left trader are valid
        $leftItemsIds = collect($request->items)->pluck('item_id')->all();
        $leftTraderInventory = Inventory::with('product')->where('martian_id', $leftTraderId)
        ->whereIn('product_id', $leftItemsIds)
        ->get();

        $itemsFound = $leftTraderInventory->pluck('product_id')->toArray();
        
        // check trader items if exist on his inventory
        $leftTraderHasInvalidItem = false;
        foreach ($request->items as $item) {
            if(!in_array($item['item_id'], $itemsFound)) {
                $leftTraderHasInvalidItem = true;
                break;
            }
        }

        // validate trade items
        if($leftTraderHasInvalidItem) {
            return response()->json([
                'message' => 'Martian left trader has invalid item',
            ], 400);
        }

        // check if items of right trader are valid
        $rightItemsIds = collect($rightTrader['items'])->pluck('item_id')->all();
        
        $rightTraderInventory = Inventory::with('product')
        ->where('martian_id', $rightTrader['martian_id'])
        ->whereIn('product_id', $rightItemsIds)
        ->get();

        // Log::info($rightItemsIds);
        // Log::info($rightTraderInventory);
        // dd('test');

        $itemsFound = $rightTraderInventory->pluck('product_id')->toArray();
        // dd($itemsFound);
        // check trader items if exist on his inventory
        $rightTraderHasInvalidItem = false;
        foreach ($rightTrader['items'] as $item) {
            if(!in_array($item['item_id'], $itemsFound)) {
                $rightTraderHasInvalidItem = true;
                break;
            }
        }

        // validate trade items
        if($rightTraderHasInvalidItem) {
            return response()->json([
                'message' => 'Martian right trader has invalid item',
            ], 400);
        }

        $leftTraderItemsTotalPoints = 0;
        foreach($request->items as $item) {
            $tradeItem = $leftTraderInventory->firstWhere('product_id', $item['item_id']);
            $leftTraderItemsTotalPoints += $tradeItem->product->points * $item['qty'];
        }

        $rightTraderItemsTotalPoints = 0;
        foreach($rightTrader['items'] as $item) {
            $tradeItem = $rightTraderInventory->firstWhere('product_id', $item['item_id']);
            $rightTraderItemsTotalPoints += $tradeItem->product->points * $item['qty'];
        }

        // check if trader points are equal
        if($leftTraderItemsTotalPoints !== $rightTraderItemsTotalPoints) {
            return response()->json([
                'message' => "Martian trader points not equal",
                'details' => [
                    $leftTraderInventory->map(function($item, $key){
                        return $item->product->only(['id','name','points']);
                    }),
                    $rightTraderInventory->map(function($item, $key){
                        return $item->product->only(['id','name','points']);
                    })
                ]
            ], 400);
        }

        if($leftTraderItemsTotalPoints == 0 && $rightTraderItemsTotalPoints == 0) {
            return response()->json([
                'message' => "Martian trader points should not be 0",
            ], 400);
        }
        
        try {
            //proceed trading
            
            DB::beginTransaction();
            // swap trade qty items from owner
            foreach($request->items as $tradeItem) {
                // less qty to owner
                DB::table('inventories')->where('martian_id', $leftTraderId)
                ->where('product_id', $tradeItem['item_id'])
                ->decrement('qty', $tradeItem['qty']);

                // add qty to trader
                DB::table('inventories')->where('martian_id', $rightTrader['martian_id'])
                ->where('product_id', $tradeItem['item_id'])
                ->increment('qty', $tradeItem['qty']);
            }

            // swap trade qty items from right trader
            foreach($rightTrader['items'] as $tradeItem) {
                // less qty to owner
                DB::table('inventories')->where('martian_id', $rightTrader['martian_id'])
                ->where('product_id', $tradeItem['item_id'])
                ->decrement('qty', $tradeItem['qty']);

                // add qty to trader
                DB::table('inventories')->where('martian_id', $leftTraderId)
                ->where('product_id', $tradeItem['item_id'])
                ->increment('qty', $tradeItem['qty']);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Error in trading',
                'details' => $e->getMessage()
            ], 400);
        }
        
        return response()->json([
            'message' => 'Martian traded successful',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
