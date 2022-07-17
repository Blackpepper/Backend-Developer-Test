<?php

namespace App\Http\Controllers;

use App\Exceptions\MartianCannotDoTradingException;
use App\Exceptions\NotEnoughSupplyException;
use App\Http\Requests\TradingRequest;
use App\Services\MartianService;
use App\Services\SupplyService;
use App\Services\TradingService;
use App\Support\MartianSupport;

class TradingController extends Controller
{
    private TradingService $tradingService;
    private MartianService $martianService;
    private SupplyService $supplyService;

    public function __construct(
        TradingService $tradingService,
        MartianService $martianService,
        SupplyService $supplyService
    ) {
        $this->tradingService = $tradingService;
        $this->martianService = $martianService;
        $this->supplyService = $supplyService;
    }

    public function trade(TradingRequest $request)
    {
        $data = $request->all();

        $seller = $this->martianService->findById($data['seller']['id']);

        if (MartianSupport::cannotTrade($seller)) {
            throw new MartianCannotDoTradingException('Martian cannot do trading.');
        }

        if ($this->supplyService->insufficientSupply($seller, $data['seller']['supplies'])) {
            throw new NotEnoughSupplyException("Not enough supply to trade.");
        }

        $buyer = $this->martianService->findById($data['buyer']['id']);

        $result = $this->tradingService->trade($buyer, $seller, $request->all());

        return response()->json(['data' => $result], 200);
    }
}
