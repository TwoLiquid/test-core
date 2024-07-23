<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\UserLabelControllerInterface;
use App\Lists\User\Label\UserLabelList;
use App\Transformers\Api\Guest\User\UserLabel\UserLabelTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserLabelController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class UserLabelController extends BaseController implements UserLabelControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user labels
         */
        $userLabelListItems = UserLabelList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($userLabelListItems, new UserLabelTransformer),
            trans('validations/api/guest/user/label/index.result.success')
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
         * Getting user label
         */
        $userLabelListItem = UserLabelList::getItem($id);

        /**
         * Checking user label existence
         */
        if (!$userLabelListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/label/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userLabelListItem, new UserLabelTransformer),
            trans('validations/api/guest/user/label/show.result.success')
        );
    }
}
