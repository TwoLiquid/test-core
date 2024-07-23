<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\AccountStatusControllerInterface;
use App\Lists\Account\Status\AccountStatusList;
use App\Transformers\Api\Guest\User\AccountStatus\AccountStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AccountStatusController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class AccountStatusController extends BaseController implements AccountStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting account statuses
         */
        $accountStatusListItems = AccountStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($accountStatusListItems, new AccountStatusTransformer),
            trans('validations/api/guest/user/account/status/index.result.success')
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
         * Getting account status
         */
        $accountStatusListItem = AccountStatusList::getItem($id);

        /**
         * Checking account status existence
         */
        if (!$accountStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/account/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($accountStatusListItem, new AccountStatusTransformer),
            trans('validations/api/guest/user/account/status/show.result.success')
        );
    }
}
