<?php

namespace App\Http\Controllers\API\Supply;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\API\Supply\CreateSupplyRequest;
use App\Http\Requests\API\Supply\UpdateSupplyRequest;
use App\Http\Resources\SupplyCollection;
use App\Http\Resources\SupplyResource;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends BaseController
{
    /**
     * Display a listing of supplies.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $supplies = Supply::get();
            $data = new SupplyCollection($supplies);
            return $this->sendSuccess($data, __('supplies.supplies.retrieved'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Store a newly created supply in storage.
     *
     * @param \App\Http\Requests\API\Supply\CreateSupplyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSupplyRequest $request)
    {
        try {
            $validated = $request->validated();
            $supply = Supply::create($validated);
            $data = new SupplyResource($supply);
            return $this->sendSuccess($data, __('supplies.supply.created'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Display the specified supply.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Supply $supply
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Supply $supply)
    {
        try {
            $data = new SupplyResource($supply);
            return $this->sendSuccess($data, __('supplies.supply.retrieved'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Update the specified supply in storage.
     *
     * @param \App\Http\Requests\API\Supply\UpdateSupplyRequest $request
     * @param \App\Models\Supply $supply
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplyRequest $request, Supply $supply)
    {
        try {
            $validated = $request->validated();
            $return = $supply->update($validated);
            $msg = ($return ? __('supplies.supply.updated') : __('supplies.supply.failed.update'));
            $data = new SupplyResource($supply);
            return $this->sendSuccess($data, $msg);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from supply.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Supply $supply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Supply $supply)
    {
        try {
            $supplyID = $supply->id;
            $return = $supply->delete();
            $msg = ($return ? __('supplies.supply.deleted') : __('supplies.supply.failed.delete'));
            $data = [
                'supply_id' => $supplyID
            ];
            return $this->sendSuccess($data, $msg);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }
}
