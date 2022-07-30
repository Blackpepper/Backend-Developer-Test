<?php

namespace App\Http\Controllers;

use App\Models\Martian;
use Illuminate\Http\Request;
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
        $martians = Martian::with('inventories')->get();
        return response()->json([
            'message' => 'Martian list with inventories',
            'data' => $martians
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
            'name' => 'required|max:255',
            'age' => 'required|numeric',
            'gender' => ['required',Rule::in(['M', 'F'])],
            'can_trade' => 'boolean'
        ]);

        $martian = Martian::create([
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'can_trade' => $request->can_trade ?? true
        ]);

        return response()->json([
            'message' => 'Martian created',
            'data' => $martian
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $martian = Martian::with('inventories')->where('id', $id)->first();

        if(!$martian) {
            return response()->json([
                'message' => 'Martian not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Martian found',
            'data' => $martian
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
            'age' => 'required|numeric',
            'gender' => ['required', Rule::in(['M', 'F'])],
            'can_trade' => 'boolean'
        ]);

        $martian = Martian::find($id);

        if(!$martian) {
            return response()->json([
                'message' => 'Martian not found',
            ], 404);
        }

        $martian->name = $request->name;
        $martian->age = $request->age;
        $martian->gender = $request->gender;
        $martian->can_trade = $request->can_trade;
        $martian->save();

        return response()->json([
            'message' => 'Martian updated',
            'data' => $martian
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
        // $martian = Martian::find($id);

        // if(!$martian) {
        //     return response()->json([
        //         'message' => 'Martian not found',
        //     ], 404);
        // }

        // $martian->delete();
    }
}
