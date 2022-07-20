<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Martians;


class MartianService
{
    public function allowedToTrade($martianid) {

        $martian = Martians::where([
            ['martianid','=',$martianid],
            ['allow','=',1],
        ])
        ->select('martianid')
        ->get();

        $allowed = ($martian->count() == 1) ? 1 : 0;

        return $allowed;
    }
}

