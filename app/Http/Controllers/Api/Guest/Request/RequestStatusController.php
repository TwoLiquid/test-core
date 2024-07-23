<?php

namespace App\Http\Controllers\Api\Guest\Request;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Request\Interfaces\RequestFieldStatusControllerInterface;
use App\Lists\Request\Status\RequestStatusList;
use App\Transformers\Api\Guest\Request\RequestStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class RequestStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Request
 */
final class RequestStatusController extends BaseController implements RequestFieldStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting request statuses
         */
        $requestStatusListItems = RequestStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($requestStatusListItems, new RequestStatusTransformer),
            trans('validations/api/guest/request/status/index.result.success')
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
         * Getting request status
         */
        $requestStatusListItem = RequestStatusList::getItem($id);

        /**
         * Checking request status existence
         */
        if (!$requestStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/request/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($requestStatusListItem, new RequestStatusTransformer),
            trans('validations/api/guest/request/status/show.result.success')
        );
    }
}
