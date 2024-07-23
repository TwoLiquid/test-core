<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\UserIdVerificationStatusControllerInterface;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Transformers\Api\Guest\User\IdVerification\UserIdVerificationStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserIdVerificationStatusController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class UserIdVerificationStatusController extends BaseController implements UserIdVerificationStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user id verification statuses
         */
        $userIdVerificationStatusListItems = UserIdVerificationStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($userIdVerificationStatusListItems, new UserIdVerificationStatusTransformer),
            trans('validations/api/guest/user/idVerification/status/index.result.success')
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
         * Getting user id verification status
         */
        $userIdVerificationStatusListItem = UserIdVerificationStatusList::getItem($id);

        /**
         * Checking user id verification status existence
         */
        if (!$userIdVerificationStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/idVerification/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userIdVerificationStatusListItem, new UserIdVerificationStatusTransformer),
            trans('validations/api/guest/user/idVerification/status/show.result.success')
        );
    }
}
