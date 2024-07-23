<?php

namespace App\Transformers\Api\Guest\Notification;

use App\Lists\Notification\Setting\Category\NotificationSettingCategoryListItem;
use App\Transformers\BaseTransformer;

/**
 * Class NotificationSettingCategoryTransformer
 *
 * @package App\Transformers\Api\Guest\Notification
 */
class NotificationSettingCategoryTransformer extends BaseTransformer
{
    /**
     * @param NotificationSettingCategoryListItem $notificationSettingCategoryListItem
     *
     * @return array
     */
    public function transform(NotificationSettingCategoryListItem $notificationSettingCategoryListItem) : array
    {
        return [
            'id'   => $notificationSettingCategoryListItem->id,
            'code' => $notificationSettingCategoryListItem->code,
            'name' => $notificationSettingCategoryListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'notification_setting_category';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'notification_setting_categories';
    }
}
