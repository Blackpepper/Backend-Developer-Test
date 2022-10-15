<?php

namespace App\Http\API\Martians\Controllers;

use App\Domain\Martians\Actions\UpsertMartianAction;
use App\Domain\Martians\Models\Martian;
use App\Http\BaseController;

/**
 * @group Martians
 *
 * APIs for managing martians
 */
class MartianController extends BaseController
{
    /**
     * List martians
     *
     * @queryParam page int The page of the list. Example: 1
     * @queryparam limit int The number of results per page. Example: 5
     */
    public function all()
    {
        return responder()->success(Martian::with('items')->newPaginate())->respond();
    }

    /**
     * Create a martian
     *
     * @bodyParam name string required The name of the martian. Example: Juan Dela Cruz
     * @bodyParam age int required The age of the martian. Example: 23
     * @bodyParam gender string required The gender of the martian. Example: Male
     * @bodyParam can_trade bool required Flagging for martian. Example: true
     * @bodyParam items object[] Items that the martian owns.
     * @bodyParam items[].id int Item ID. Example: 1
     * @bodyParam items[].quantity int Item quantity. Example: 5
     */
    public function store(UpsertMartianAction $action)
    {
        return responder()->success($action->do(request()->input()))->respond(201);
    }

    /**
     * Update a martian
     *
     * @bodyParam name string The name of the martian. Example: Juan Dela Cruz
     * @bodyParam age int The age of the martian. Example: 23
     * @bodyParam gender string The gender of the martian. Example: Male
     * @bodyParam can_trade bool Flagging for martian. Example: true
     * @bodyParam items object[] Items that the martian owns.
     * @bodyParam items[].id int Item ID. Example: 1
     * @bodyParam items[].quantity int Item quantity. Example: 5
     */
    public function update(Martian $martian, UpsertMartianAction $action)
    {
        return responder()->success($action->do(request()->input(), $martian))->respond();
    }
}
