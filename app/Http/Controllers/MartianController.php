<?php

namespace App\Http\Controllers;

use App\Http\Resources\MartianCollection;
use App\Http\Resources\MartianResource;
use App\Models\Martian;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $martian = Martian::create($request->only([
            'name',
            'age',
            'gender'
        ]));

        return new MartianResource($martian);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Martian  $martian
     * @return \Illuminate\Http\Response
     */
    public function show(Martian $martian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Martian  $martian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Martian $martian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Martian  $martian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Martian $martian)
    {
        //
    }
}
