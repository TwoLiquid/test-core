<?php

namespace App\Services\Notification\Interfaces;

use App\Models\MySql\User\User;

/**
 * Interface NotificationSettingServiceInterface
 *
 * @package App\Services\Notification\Interfaces
 */
interface NotificationSettingServiceInterface
{
    /**
     * This method provides getting data
     * by related entity repositories
     *
     * @param User $user
     *
     * @return array
     */
    public function getForUserSettings(
        User $user
    ) : array;
}