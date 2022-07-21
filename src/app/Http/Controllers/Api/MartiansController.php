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

    public function trade(Request $request) {
        if($request->isMethod('post')) {
            $postData = $request->input();
            $tradePost = unserialize($postData['data']);

            $trader1 = $tradePost['trade']['buyFrom']['items'];
            $trader2 = $tradePost['trade']['sellTo']['items'];

            $MatchPoints = $this->martianService->tradeMatchPoints($trader1, $trader2);

            $trader1_martianid = $tradePost['trade']['buyFrom']['martianid'];
            $trader2_martianid = $tradePost['trade']['sellTo']['martianid'];

            $trader1allow = $this->martianService->allowedToTrade($trader1_martianid);
            $trader2allow = $this->martianService->allowedToTrade($trader2_martianid);

            $stockStatus = [
                'buyFrom' => [
                    'martianid' => $trader1_martianid,
                    'trader1' => $trader1,
                ],
                'sellTo' => [
                    'martianid' => $trader2_martianid,
                    'trader2' => $trader2,
                ],

            ];

            $InStock = (new InventorySuppliesController())->tradeInStock($stockStatus);

            if(($MatchPoints && $trader1allow && $trader2allow && $InStock) == 1 && $trader1_martianid != $trader2_martianid) {

                $trader1UpdateInventory = (new InventorySuppliesController())->updateSupplies($trader2, $trader1_martianid, $trader2_martianid);

                $trader2UpdateInventory = (new InventorySuppliesController())->updateSupplies($trader1, $trader2_martianid, $trader1_martianid);
                
            }

            $msg = '';
            $status = '';

            if($MatchPoints != 1) {
                $msg .= 'Both side of the trade does not match amount of points!<br/>';
            }

            if($trader1allow != 1 || $trader2allow != 1) {
                $msg .= 'One of the trader is not allowed to!<br>';
            }

            if($trader1_martianid == $trader2_martianid) {
                $msg .= 'Not allowed!';
            }

            if($InStock != 1 ) {
                $msg .= 'One of the item stock is not enough to trade!<br/>';
            }

            $status = (!empty($msg)) ? false : true;
            $msg = (!empty($msg)) ? 'Error trading! '.$msg : 'Successfully Traded!';
            

            return [
                'status' => $status,
                'msg' => $msg,
            ];

        } else {
            return [
                'status' => false,
                'msg' => 'Error!',
            ];
        }
    }
}
