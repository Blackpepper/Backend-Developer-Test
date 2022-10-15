<?php

namespace App\Http\API\Items\Controllers;

use App\Domain\Items\Actions\UpsertItemAction;
use App\Domain\Items\Models\Item;
use App\Http\BaseController;

/**
 * @group Items
 *
 * APIs for managing items
 */
class ItemController extends BaseController
{
    /**
     * List items
     *
     * @queryParam page int The page of the list. Example: 1
     * @queryparam limit int The number of results per page. Example: 5
     */
    public function all()
    {
        return responder()->success(Item::newPaginate())->respond();
    }

    /**
     * Create an item
     *
     * @bodyParam name string required The name of the item. Example: Knife
     * @bodyParam points int required The points of the item. Example: 10
     */
    public function store(UpsertItemAction $action)
    {
        return responder()->success($action->do(request()->input()))->respond(201);
    }

    /**
     * Update an item
     *
     * @bodyParam name string The name of the item. Example: Axe
     * @bodyParam points int The points of the item. Example: 15
     */
    public function update(Item $item, UpsertItemAction $action)
    {
        return responder()->success($action->do(request()->input(), $item))->respond();
    }
}
