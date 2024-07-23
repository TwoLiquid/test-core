<?php

namespace App\Http\Controllers\Api\Guest\Request;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Request\Interfaces\RequestTypeControllerInterface;
use App\Lists\Request\Type\RequestTypeList;
use App\Transformers\Api\Guest\Request\RequestTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class RequestTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Request
 */
final class RequestTypeController extends BaseController implements RequestTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a request types
         */
        $requestTypeListItems = RequestTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($requestTypeListItems, new RequestTypeTransformer),
            trans('validations/api/guest/request/type/index.result.success')
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
         * Getting a request type
         */
        $requestTypeListItem = RequestTypeList::getItem($id);

        /**
         * Checking request type existence
         */
        if (!$requestTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/request/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($requestTypeListItem, new RequestTypeTransformer),
            trans('validations/api/guest/request/type/show.result.success')
        );
    }
}
