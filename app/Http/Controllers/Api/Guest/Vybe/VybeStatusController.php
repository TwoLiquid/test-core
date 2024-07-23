<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeStatusControllerInterface;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Transformers\Api\Guest\Vybe\Status\VybeStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeStatusController extends BaseController implements VybeStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting vybe statuses
         */
        $vybeStatusListItems = VybeStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeStatusListItems, new VybeStatusTransformer),
            trans('validations/api/guest/vybe/status/index.result.success')
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
         * Getting vybe status
         */
        $vybeStatusListItem = VybeStatusList::getItem($id);

        /**
         * Checking vybe status existence
         */
        if (!$vybeStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/vybe/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeStatusListItem, new VybeStatusTransformer),
            trans('validations/api/guest/vybe/status/show.result.success')
        );
    }
}
