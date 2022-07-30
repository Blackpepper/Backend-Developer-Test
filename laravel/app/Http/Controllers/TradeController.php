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
            'right_trader' => 'required',
            'right_trader.martian_id' => 'required',
            'right_trader.items' => 'required|array',
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
        $leftTraderInventory = Inventory::where('martian_id', $leftTraderId)
        ->whereIn('id', $request->items)
        ->get();

        $itemsFound = $leftTraderInventory->pluck('id')->toArray();
        
        // check trader items if exist on his inventory
        $leftTraderHasInvalidItem = false;
        foreach ($request->items as $item) {
            if(!in_array($item, $itemsFound)) {
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
        $rightTraderInventory = Inventory::where('martian_id', $rightTrader['martian_id'])
        ->whereIn('id', $rightTrader['items'])
        ->get();

        $itemsFound = $rightTraderInventory->pluck('id')->toArray();
        
        // check trader items if exist on his inventory
        $rightTraderHasInvalidItem = false;
        foreach ($rightTrader['items'] as $item) {
            if(!in_array($item, $itemsFound)) {
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

        $leftTraderItemsTotalPoints = $leftTraderInventory->sum('points');
        $rightTraderItemsTotalPoints = $rightTraderInventory->sum('points');

        // check if trader points are equal
        if($leftTraderItemsTotalPoints !== $rightTraderItemsTotalPoints) {
            return response()->json([
                'message' => "Martian trader points not equal",
                'details' => [
                    $leftTraderInventory->map(function ($left, $key) {
                        return $left->only(['id','name','points']);
                    }),
                    $rightTraderInventory->map(function ($left, $key) {
                        return $left->only(['id','name','points']);
                    }),
                ]
            ], 400);
        }

        try {
            //proceed trading
            // DB::connection()->enableQueryLog();
            DB::beginTransaction();
            //swap trader items owner by martian_id on inventories table
            $leftItemIds = $leftTraderInventory->pluck('id')->all();
            $rightItemsIds = $rightTraderInventory->pluck('id')->all();

            // left trader items will change martian_id to right martian_id
            DB::table('inventories')->whereIn('id', $leftItemIds)->update([
                'martian_id' => $rightTrader['martian_id']
            ]);

            // right trader items will change martian_id to left martian_id
            DB::table('inventories')->whereIn('id', $rightItemsIds)->update([
                'martian_id' => $leftTraderId
            ]);
            // $queries = DB::getQueryLog();
            // Log::info($queries);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
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
