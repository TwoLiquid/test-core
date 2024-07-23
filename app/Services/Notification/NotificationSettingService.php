<?php

namespace App\Services\Notification;

use App\Lists\Notification\Setting\NotificationSettingList;
use App\Lists\Notification\Setting\NotificationSettingListItem;
use App\Lists\Notification\Setting\Category\NotificationSettingCategoryList;
use App\Lists\Notification\Setting\Category\NotificationSettingCategoryListItem;
use App\Lists\Notification\Setting\Subcategory\NotificationSettingSubcategoryList;
use App\Lists\Notification\Setting\Subcategory\NotificationSettingSubcategoryListItem;
use App\Models\MySql\User\User;
use App\Services\Notification\Interfaces\NotificationSettingServiceInterface;

/**
 * Class NotificationService
 *
 * @package App\Services\Notification
 */
class NotificationSettingService implements NotificationSettingServiceInterface
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function getForUserSettings(
        User $user
    ) : array
    {
        $notificationSettingsData = [];
        $userNotificationSettings = $user->notificationSettings;

        /** @var NotificationSettingCategoryListItem $notificationSettingCategory */
        foreach (NotificationSettingCategoryList::getItems() as $notificationSettingCategory) {

            $notificationSettingSubcategoryData = [];

            /** @var NotificationSettingSubcategoryListItem $notificationSettingSubcategory */
            foreach (NotificationSettingSubcategoryList::getItemsByCategory(
                $notificationSettingCategory
            ) as $notificationSettingSubcategory) {

                $notificationSettingData = [];

                /** @var NotificationSettingListItem $notificationSetting */
                foreach (NotificationSettingList::getItemsBySubcategory(
                    $notificationSettingSubcategory
                ) as $notificationSetting) {
                    $notificationCode = $notificationSetting->code;

                    $notificationSettingData[] = [
                        'id'    => $notificationSetting->id,
                        'code'  => $notificationSetting->code,
                        'name'  => $notificationSetting->name,
                        'value' => $userNotificationSettings->$notificationCode
                    ];
                }

                $notificationSettingSubcategoryData[] = [
                    'id'       => $notificationSettingSubcategory->id,
                    'code'     => $notificationSettingSubcategory->code,
                    'name'     => $notificationSettingSubcategory->name,
                    'settings' => $notificationSettingData
                ];
            }

            $notificationSettingsData['notification_settings'][] = [
                'id'            => $notificationSettingSubcategory->id,
                'code'          => $notificationSettingSubcategory->code,
                'name'          => $notificationSettingSubcategory->name,
                'subcategories' => $notificationSettingSubcategoryData
            ];
        }

        return $notificationSettingsData;
    }
}