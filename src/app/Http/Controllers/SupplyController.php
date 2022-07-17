<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplyCreateRequest;
use App\Http\Resources\SupplyResource;
use App\Models\Supply;
use App\Services\MartianService;
use App\Services\SupplyService;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    private SupplyService $supplyService;

    private MartianService $martianService;

    public function __construct(SupplyService $supplyService, MartianService $martianService)
    {
        $this->supplyService = $supplyService;
        $this->martianService = $martianService;
    }

    public function index()
    {
        //
    }

    public function store(SupplyCreateRequest $request)
    {
        $martian = $this->martianService->findById($request->input('martian_id'));

        return new SupplyResource($this->supplyService->create($martian, $request->validated()));
    }

    public function show(Supply $supply)
    {
        //
    }

    public function update(Request $request, Supply $supply)
    {
        //
    }

    public function destroy(Supply $supply)
    {
        //
    }
}
