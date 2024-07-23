<?php

namespace App\Http\Controllers\Api\Guest\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\User\Interfaces\UserThemeControllerInterface;
use App\Lists\User\Theme\UserThemeList;
use App\Transformers\Api\Guest\User\Theme\UserThemeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserThemeController
 *
 * @package App\Http\Controllers\Api\Guest\User
 */
final class UserThemeController extends BaseController implements UserThemeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user themes
         */
        $userThemeListItems = UserThemeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($userThemeListItems, new UserThemeTransformer),
            trans('validations/api/guest/user/theme/index.result.success')
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
         * Getting user theme
         */
        $userThemeListItem = UserThemeList::getItem($id);

        /**
         * Checking user theme existence
         */
        if (!$userThemeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/user/theme/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userThemeListItem, new UserThemeTransformer),
            trans('validations/api/guest/user/theme/show.result.success')
        );
    }
}
