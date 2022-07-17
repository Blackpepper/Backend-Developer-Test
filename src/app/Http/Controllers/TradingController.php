<?php

namespace App\Http\Controllers;

use App\Http\Requests\TradingRequest;
use App\Services\MartianService;
use App\Services\TradingService;
use App\Support\MartianSupport;

class TradingController extends Controller
{
    private TradingService $tradingService;
    private MartianService $martianService;

    public function __construct(
        TradingService $tradingService,
        MartianService $martianService
    ) {
        $this->tradingService = $tradingService;
        $this->martianService = $martianService;
    }

    public function trade(TradingRequest $request)
    {
        $data = $request->all();

        $seller = $this->martianService->findById($data['seller']['id']);

        if (MartianSupport::cannotTrade($seller)) {
            return response()->json(['data' => 'Seller was flagged.'], 403);
        }

        $buyer = $this->martianService->findById($data['buyer']['id']);

        $result = $this->tradingService->trade($buyer, $seller, $request->all());

        return response()->json(['data' => $result], 200);
    }
}
