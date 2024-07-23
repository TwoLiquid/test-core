<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybePeriodControllerInterface;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Transformers\Api\Guest\Vybe\Period\VybePeriodTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybePeriodController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybePeriodController extends BaseController implements VybePeriodControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe periods
         */
        $vybePeriodListItems = VybePeriodList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybePeriodListItems, new VybePeriodTransformer),
            trans('validations/api/guest/vybe/period/index.result.success')
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
         * Getting vybe period
         */
        $vybePeriodListItem = VybePeriodList::getItem($id);

        /**
         * Checking vybe period existence
         */
        if (!$vybePeriodListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/period/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybePeriodListItem, new VybePeriodTransformer),
            trans('validations/api/guest/vybe/period/show.result.success')
        );
    }
}
