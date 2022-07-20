<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Martians;
use App\Models\InventorySupplies;
use App\Http\Controllers\Api\InventorySuppliesController;
use App\Http\Resources\MartianResource;
use App\Services\MartianService;

class MartiansController extends Controller
{
    private $martianService;

    public function __construct(MartianService $service) 
    {
        $this->martianService = $service;
    }

    public function index() {
        $martian = Martians::Where('allow','!=','0')
        ->with(['inventorysupplies']);
    
        return MartianResource::collection($martian->get());
    }

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

    public function show($martianid=null) {
        if(!empty($martianid) && $this->martianService->allowedToTrade($martianid) == 1) {
            $martian = Martians::find($martianid);

            $inventorysup = InventorySupplies::from('inventory_supplies as isup')
            ->where('isup.martianid','=',$martianid)
            ->leftjoin('price_table as pt', 'isup.itemid', '=', 'pt.itemid')  
            ->select('isup.itemid', 'isup.quantity', 'pt.name', 'pt.points')
            ->get();

            $inventorysuplist = [];
            foreach($inventorysup as $item) {
                $inventorysuplist[] = $item;
            }

            return [
                'status' => true,
                'data' => [
                    'martianid' => $martian->martianid,
                    'name' => $martian->name,
                    'age' => $martian->age,
                    'allow' => $martian->allow,   
                    'inventory' => $inventorysuplist,
                ] 
            ];
            
        } else {
            return [
                'status' => false,
                'data' => '',
            ];
        }
    }
}
