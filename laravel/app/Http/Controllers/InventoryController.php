<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Martian;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventories = Inventory::all();
        return response()->json([
            'message' => 'Inventory List',
            'data' => $inventories
        ], 200);
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
            'martian_id' => 'required|numeric',
            'name' => 'required|max:255',
            'points' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);

        $martian = Martian::find($request->martian_id);

        if(!$martian) {
            return response()->json([
                'message' => 'Invalid martian_id',
            ], 404);
        }
        
        $inventory = Inventory::create([
            'martian_id' => $request->martian_id,
            'name' => $request->name,
            'points' => $request->points,
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'message' => 'Inventory created',
            'data' => $inventory
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
        $inventory = Inventory::with('martian')->where('id', $id)->first();

        if(!$inventory) {
            return response()->json([
                'message' => 'Inventory not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Inventory found',
            'data' => $inventory
        ], 200);
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
        $request->validate([
            'name' => 'required|max:255',
            'points' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);
        
        $inventory = Inventory::find($id);

        if(!$inventory) {
            return response()->json([
                'message' => 'Inventory not found',
            ], 404);
        }

        $inventory->name = $request->name;
        $inventory->points = $request->points;
        $inventory->quantity = $request->quantity;
        $inventory->save();

        return response()->json([
            'message' => 'Inventory updated',
            'data' => $inventory
        ], 200);
    }

    public function updateInventoryStocks(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        if(!$inventory) {
            return response()->json([
                'message' => 'Inventory not found',
            ], 404);
        }

        $inventory->quantity = $request->stock_quantity;
        $inventory->save();

        return response()->json([
            'message' => 'Inventory stock updated',
            'data' => $inventory
        ], 200);
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
