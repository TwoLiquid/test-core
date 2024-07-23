<?php

namespace App\Http\Controllers\Api\Guest\Request;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Request\Interfaces\RequestGroupControllerInterface;
use App\Lists\Request\Group\RequestGroupList;
use App\Transformers\Api\Guest\Request\RequestGroupTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class RequestGroupController
 *
 * @package App\Http\Controllers\Api\Guest\Request
 */
final class RequestGroupController extends BaseController implements RequestGroupControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a request groups
         */
        $requestGroupListItems = RequestGroupList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($requestGroupListItems, new RequestGroupTransformer),
            trans('validations/api/guest/request/group/index.result.success')
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
         * Getting a request group
         */
        $requestGroupListItem = RequestGroupList::getItem($id);

        /**
         * Checking request group existence
         */
        if (!$requestGroupListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/request/group/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($requestGroupListItem, new RequestGroupTransformer),
            trans('validations/api/guest/request/group/show.result.success')
        );
    }
}
