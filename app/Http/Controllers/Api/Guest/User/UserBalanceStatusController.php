<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\UserBalanceStatusControllerInterface;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Transformers\Api\Guest\User\BalanceStatus\UserBalanceStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserBalanceStatusController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class UserBalanceStatusController extends BaseController implements UserBalanceStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user balance statuses
         */
        $userBalanceStatusListItems = UserBalanceStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($userBalanceStatusListItems, new UserBalanceStatusTransformer),
            trans('validations/api/guest/user/balance/status/index.result.success')
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
         * Getting user balance status
         */
        $userBalanceStatusListItem = UserBalanceStatusList::getItem($id);

        /**
         * Checking user balance existence
         */
        if (!$userBalanceStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/balance/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userBalanceStatusListItem, new UserBalanceStatusTransformer),
            trans('validations/api/guest/user/balance/status/show.result.success')
        );
    }
}
