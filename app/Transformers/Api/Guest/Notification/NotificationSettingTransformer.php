<?php

namespace App\Transformers\Api\Guest\Notification;

use App\Lists\Notification\Setting\NotificationSettingListItem;
use App\Transformers\BaseTransformer;

/**
 * Class NotificationSettingTransformer
 *
 * @package App\Transformers\Api\Guest\Notification
 */
class NotificationSettingTransformer extends BaseTransformer
{
    /**
     * @param NotificationSettingListItem $notificationSettingListItem
     *
     * @return array
     */
    public function transform(NotificationSettingListItem $notificationSettingListItem) : array
    {
        return [
            'id'   => $notificationSettingListItem->id,
            'code' => $notificationSettingListItem->code,
            'name' => $notificationSettingListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'notification_setting';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'notification_settings';
    }
}
