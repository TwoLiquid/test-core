<?php

namespace App\Http\Controllers\Api\Guest\Request;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Request\Interfaces\RequestFieldStatusControllerInterface;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Transformers\Api\Guest\Request\RequestFieldStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class RequestFieldStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Request
 */
final class RequestFieldStatusController extends BaseController implements RequestFieldStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting request field statuses
         */
        $requestFieldStatusListItems = RequestFieldStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($requestFieldStatusListItems, new RequestFieldStatusTransformer),
            trans('validations/api/guest/request/field/status/index.result.success')
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
         * Getting request field status
         */
        $requestFieldStatusListItem = RequestFieldStatusList::getItem($id);

        /**
         * Checking request field status existence
         */
        if (!$requestFieldStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/request/field/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($requestFieldStatusListItem, new RequestFieldStatusTransformer),
            trans('validations/api/guest/request/field/status/show.result.success')
        );
    }
}
