<?php

namespace App\Http\Controllers\Api\Guest\Notification;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Notification\Interfaces\NotificationSettingControllerInterface;
use App\Lists\Notification\Setting\NotificationSettingList;
use App\Transformers\Api\Guest\Notification\NotificationSettingTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationSettingController
 *
 * @package App\Http\Controllers\Api\Guest\Setting\Notification
 */
final class NotificationSettingController extends BaseController implements NotificationSettingControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting notification settings
         */
        $notificationSettingListItems = NotificationSettingList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($notificationSettingListItems, new NotificationSettingTransformer),
            trans('validations/api/guest/notification/setting/index.result.success')
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
         * Getting notification setting
         */
        $notificationSettingListItem = NotificationSettingList::getItem($id);

        /**
         * Checking notification setting existence
         */
        if (!$notificationSettingListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/notification/setting/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($notificationSettingListItem, new NotificationSettingTransformer),
            trans('validations/api/guest/notification/setting/show.result.success')
        );
    }
}
