<?php

namespace App\Http\Controllers;

use App\Http\Requests\MartianCreateRequest;
use App\Http\Resources\MartianResource;
use Illuminate\Http\Request;

class MartianController extends Controller
{

    public function store(MartianCreateRequest $request): MartianResource
    {
        return new MartianResource();
    }
}
