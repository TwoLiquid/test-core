<?php

namespace App\Http\Controllers\Api\Guest\Notification;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Notification\Interfaces\NotificationSettingSubcategoryControllerInterface;
use App\Lists\Notification\Setting\Subcategory\NotificationSettingSubcategoryList;
use App\Transformers\Api\Guest\Notification\NotificationSettingSubcategoryTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationSettingSubcategoryController
 *
 * @package App\Http\Controllers\Api\Guest\Setting\Notification
 */
final class NotificationSettingSubcategoryController extends BaseController implements NotificationSettingSubcategoryControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting notification setting subcategories
         */
        $notificationSettingSubcategoryListItems = NotificationSettingSubcategoryList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($notificationSettingSubcategoryListItems, new NotificationSettingSubcategoryTransformer),
            trans('validations/api/guest/notification/setting/subcategory/index.result.success')
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
         * Getting notification setting subcategories
         */
        $notificationSettingSubcategoryListItem = NotificationSettingSubcategoryList::getItem($id);

        /**
         * Checking notification setting subcategory existence
         */
        if (!$notificationSettingSubcategoryListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/notification/setting/subcategory/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($notificationSettingSubcategoryListItem, new NotificationSettingSubcategoryTransformer),
            trans('validations/api/guest/notification/setting/subcategory/show.result.success')
        );
    }
}
