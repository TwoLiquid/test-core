<?php

namespace App\Settings\Base;

use App\Models\MySql\User\User;
use App\Repositories\User\UserSettingRepository;
use App\Settings\Base\Interfaces\UserSettingInterface;

/**
 * Class UserSetting
 *
 * @package App\Settings\Base
 */
abstract class UserSetting extends BaseSetting implements UserSettingInterface
{
    /**
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * @var UserSettingRepository
     */
    protected UserSettingRepository $userSettingRepository;

    /**
     * UserSetting constructor
     */
    public function __construct()
    {
        /** @var UserSettingRepository userSettingRepository */
        $this->userSettingRepository = new UserSettingRepository();
    }

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return User|null
     */
    public function getUser() : ?User
    {
        return $this->user;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param User $user
     */
    public function setUser(
        User $user
    ) : void
    {
        $this->user = $user;
    }
}