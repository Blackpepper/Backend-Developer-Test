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
        $inventories = Inventory::where('martian_id', $martianId)->get();
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
    public function store(Request $request, $martianId)
    {
        $request->validate([
            'name' => 'required|max:255',
            'points' => 'required|numeric',
        ]);

        $martian = Martian::find($martianId);

        if(!$martian) {
            return response()->json([
                'message' => 'Invalid martian_id',
            ], 404);
        }
        
        $inventory = Inventory::create([
            'martian_id' => $martianId,
            'name' => $request->name,
            'points' => $request->points
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
        $inventory = Inventory::where('martian_id', $martianId)
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
    public function update(Request $request, $martianId, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'points' => 'required|numeric',
        ]);
        
        $inventory = Inventory::where('martian_id', $martianId)->where('id', $id)->first();

        if(!$inventory) {
            return response()->json([
                'message' => 'Inventory not found',
            ], 404);
        }

        $inventory->name = $request->name;
        $inventory->points = $request->points;
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
