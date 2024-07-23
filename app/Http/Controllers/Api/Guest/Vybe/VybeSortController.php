<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeSortControllerInterface;
use App\Lists\Vybe\Sort\VybeSortList;
use App\Transformers\Api\Guest\Vybe\Sort\VybeSortTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeSortController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeSortController extends BaseController implements VybeSortControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe sorts
         */
        $vybeSortListItems = VybeSortList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeSortListItems, new VybeSortTransformer),
            trans('validations/api/guest/vybe/sort/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe sort
         */
        $vybeSortListItem = VybeSortList::getItem($id);

        /**
         * Checking vybe sort existence
         */
        if (!$vybeSortListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/sort/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeSortListItem, new VybeSortTransformer),
            trans('validations/api/guest/vybe/sort/show.result.success')
        );
    }
}
