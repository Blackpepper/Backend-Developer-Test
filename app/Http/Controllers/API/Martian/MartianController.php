<?php

namespace App\Http\Controllers\API\Martian;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\API\Martian\CreateMartianRequest;
use App\Http\Requests\API\Martian\TradeRequest;
use App\Http\Requests\API\Martian\UpdateMartianRequest;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\MartianCollection;
use App\Http\Resources\MartianResource;
use App\Models\Martian;
use App\Services\CompareSuppliesService;
use App\Services\CreateMartianService;
use App\Services\TradeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MartianController extends BaseController
{
    /**
     * Display a listing of martians.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $martians = Martian::with(Martian::$relation)->get();
            $data = new MartianCollection($martians);
            return $this->sendSuccess($data, __('martians.martians.retrieved'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Store a newly created martian in storage.
     *
     * @param \App\Http\Requests\API\Martian\CreateMartianRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMartianRequest $request)
    {
        try {
            // get validated params
            $validated = $request->validated();

            // create martian
            $martian = CreateMartianService::createMartian($validated);

            $martian->load(Martian::$relation);
            $data = new MartianResource($martian);
            return $this->sendSuccess($data, __('martians.martian.created'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Display the specified martian.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Martian $martian
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Martian $martian)
    {
        try {
            $martian->load(Martian::$relation);
            $data = new MartianResource($martian);
            return $this->sendSuccess($data, __('martians.martian.retrieved'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Update the specified martian in storage.
     *
     * @param \App\Http\Requests\API\Martian\UpdateMartianRequest $request
     * @param \App\Models\Martian $martian
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMartianRequest $request, Martian $martian)
    {
        try {
            $validated = $request->validated();
            $return = $martian->update($validated);
            $msg = ($return ? __('martians.martian.updated') : __('martians.martian.failed.update'));

            $martian->load(Martian::$relation);
            $data = new MartianResource($martian);
            return $this->sendSuccess($data, $msg);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Remove the specified martian from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Martian $martian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Martian $martian)
    {
        try {
            $martianID = $martian->id;
            $return = $martian->delete();
            $msg = ($return ? __('martians.martian.deleted') : __('martians.martian.failed.delete'));
            $data = [
                'martian_id' => $martianID
            ];
            return $this->sendSuccess($data, $msg);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->sendError($ex->getMessage());
        }
    }
}
