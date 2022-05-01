<?php

namespace App\Services;

use App\Models\Martian;

interface CreateMartian
{
    /**
     * Create martian
     *
     * @param $data
     * @return Martian
     */
    public static function createMartian($data);
}
