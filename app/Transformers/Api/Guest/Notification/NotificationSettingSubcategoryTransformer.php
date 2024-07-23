<?php

namespace App\Transformers\Api\Guest\Notification;

use App\Lists\Notification\Setting\Subcategory\NotificationSettingSubcategoryListItem;
use App\Transformers\BaseTransformer;

/**
 * Class NotificationSettingSubcategoryTransformer
 *
 * @package App\Transformers\Api\Guest\Notification
 */
class NotificationSettingSubcategoryTransformer extends BaseTransformer
{
    /**
     * @param NotificationSettingSubcategoryListItem $notificationSettingSubcategoryListItem
     *
     * @return array
     */
    public function transform(NotificationSettingSubcategoryListItem $notificationSettingSubcategoryListItem) : array
    {
        return [
            'id'   => $notificationSettingSubcategoryListItem->id,
            'code' => $notificationSettingSubcategoryListItem->code,
            'name' => $notificationSettingSubcategoryListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'notification_setting_subcategory';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'notification_setting_subcategories';
    }
}
