<?php

namespace App\Settings\Base\Interfaces;

use App\Models\MySql\User\User;

/**
 * Interface BaseSettingInterface
 *
 * @package App\Settings\Base\Interfaces
 */
interface UserSettingInterface
{
    /**
     * This method provides getting data
     *
     * @return User|null
     */
    public function getUser() : ?User;

    /**
     * This method provides setting data
     *
     * @param User $user
     */
    public function setUser(
        User $user
    ) : void;
}