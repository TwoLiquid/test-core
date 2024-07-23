<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\UserBalanceTypeControllerInterface;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Transformers\Api\Guest\User\BalanceType\UserBalanceTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserBalanceTypeController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class UserBalanceTypeController extends BaseController implements UserBalanceTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a user balance types
         */
        $userBalanceTypeListItems = UserBalanceTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($userBalanceTypeListItems, new UserBalanceTypeTransformer),
            trans('validations/api/guest/user/balance/type/index.result.success')
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
         * Getting a user balance type
         */
        $userBalanceTypeListItem = UserBalanceTypeList::getItem($id);

        /**
         * Checking user balance existence
         */
        if (!$userBalanceTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/balance/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userBalanceTypeListItem, new UserBalanceTypeTransformer),
            trans('validations/api/guest/user/balance/type/show.result.success')
        );
    }
}
