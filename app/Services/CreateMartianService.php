<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Martian;
use Illuminate\Support\Facades\Log;

class CreateMartianService implements CreateMartian
{
    /**
     * Create martian
     *
     * @param $data
     * @return Martian
     */
    public static function createMartian($data)
    {
        $martian = Martian::create($data);
        if (isset($data['supplies'])) {
            foreach ($data['supplies'] as $supply) {
                Inventory::create([
                    'martian_id' => $martian->id,
                    'supply_id' => $supply['id'],
                    'supply_quantity' => $supply['quantity']
                ]);
            }
        }
        return $martian;
    }
}
