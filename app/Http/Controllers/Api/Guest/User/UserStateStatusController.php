<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\UserStateStatusControllerInterface;
use App\Lists\User\State\Status\UserStateStatusList;
use App\Transformers\Api\Guest\User\StateStatus\UserStateStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserStateStatusController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class UserStateStatusController extends BaseController implements UserStateStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user state statuses
         */
        $userStateStatusListItems = UserStateStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($userStateStatusListItems, new UserStateStatusTransformer),
            trans('validations/api/guest/user/state/status/index.result.success')
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
         * Getting user state status
         */
        $userStateStatusListItem = UserStateStatusList::getItem($id);

        /**
         * Checking user state status existence
         */
        if (!$userStateStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/state/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userStateStatusListItem, new UserStateStatusTransformer),
            trans('validations/api/guest/user/state/status/show.result.success')
        );
    }
}
