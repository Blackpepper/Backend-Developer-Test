<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Martians;
use App\Http\Controllers\Api\InventorySuppliesController;

class MartiansController extends Controller
{
    public function addmartian(Request $request) {
        $postData = $request->input();
        $martianPost = unserialize($postData['data']);

        if($request->isMethod('post')) {
            $martian = new Martians;
            $martian->name = $martianPost['name'];
            $martian->age = $martianPost['age'];
            $martian->gender = $martianPost['gender'];
            $martian->allow = $martianPost['allow'];
            $martian->save();
            $martianid = $martian->martianid;

            if(!empty($martianPost['inventory'])) {
                $addsupplies = (new InventorySuppliesController())->addsupplies($martianPost['inventory'], $martianid);
            }

            return [
                'status' => true,
                'msg' => "Successfully Inserted!",
            ];

        } else {
            return [
                'status' => false,
                'msg' => 'Error!',
            ];
        }

    }
}
