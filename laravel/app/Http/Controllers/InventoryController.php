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
    public function index($martianId)
    {
        $inventories = Inventory::with('product')
        ->where('martian_id', $martianId)
        ->get();

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
            'martian_id' => 'required',
            'product_id' => 'required',
            'qty' => 'required|numeric'
        ]);
        
        $inventory = Inventory::create([
            'martian_id' => $request->martian_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
        ]);

        return response()->json([
            'message' => 'Inventory created',
            'data' => $inventory
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($martianId, $id)
    {
        $inventory = Inventory::with('product')
        ->where('martian_id', $martianId)
        ->where('id', $id)
        ->first();

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
            'martian_id' => 'required',
            'qty' => 'required|numeric'
        ]);
        
        $inventory = Inventory::where('martian_id', $request->martian_id)->where('id', $id)->first();

        if(!$inventory) {
            return response()->json([
                'message' => 'Inventory not found',
            ], 404);
        }

        $inventory->qty = $request->qty;
        $inventory->save();

        return response()->json([
            'message' => 'Inventory updated',
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
