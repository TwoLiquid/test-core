<?php

namespace App\Http\Controllers\Api\Guest\Notification;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Notification\Interfaces\NotificationSettingCategoryControllerInterface;
use App\Lists\Notification\Setting\Category\NotificationSettingCategoryList;
use App\Transformers\Api\Guest\Notification\NotificationSettingCategoryTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationSettingCategoryController
 *
 * @package App\Http\Controllers\Api\Guest\Setting\Notification
 */
final class NotificationSettingCategoryController extends BaseController implements NotificationSettingCategoryControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting notification setting categories
         */
        $notificationSettingCategoryListItems = NotificationSettingCategoryList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($notificationSettingCategoryListItems, new NotificationSettingCategoryTransformer),
            trans('validations/api/guest/notification/setting/category/index.result.success')
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
         * Getting notification setting category
         */
        $notificationSettingCategoryListItem = NotificationSettingCategoryList::getItem($id);

        /**
         * Checking notification setting category existence
         */
        if (!$notificationSettingCategoryListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/notification/setting/category/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($notificationSettingCategoryListItem, new NotificationSettingCategoryTransformer),
            trans('validations/api/guest/notification/setting/category/show.result.success')
        );
    }
}
