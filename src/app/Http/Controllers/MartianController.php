<?php

namespace App\Http\Controllers;

use App\Http\Requests\MartianCreateRequest;
use App\Http\Requests\MartianUpdateRequest;
use App\Http\Resources\MartianResource;
use App\Models\Martian;
use App\Services\MartianService;
use Illuminate\Http\Request;

class MartianController extends Controller
{
    private MartianService $martianService;

    public function __construct(MartianService $martianService)
    {
        $this->martianService = $martianService;
    }

    public function store(MartianCreateRequest $request): MartianResource
    {
        return new MartianResource(
            $this->martianService->create($request->validated())
        );
    }

    public function update(MartianUpdateRequest $request, Martian $martian)
    {
        return new MartianResource(
            $this->martianService->update($martian, $request->validated())
        );
    }
}
