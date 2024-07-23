<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\Setting\Type\SettingTypeListItem;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserSettingRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserSettingRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return UserSetting|null
     */
    public function findByCodeDefault(
        string $blockCode,
        string $settingCode
    ) : ?UserSetting;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return UserSetting|null
     */
    public function findByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode
    ) : ?UserSetting;

    /**
     * This method provides finding rows
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     */
    public function getByCodesDefault(
        string $blockCode,
        array $settingsCodes
    ) : Collection;

    /**
     * This method provides finding rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     */
    public function getByCodesCustom(
        User $user,
        string $blockCode,
        array $settingsCodes
    ) : Collection;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     */
    public function existsByCodeDefault(
        string $blockCode,
        string $settingCode
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param SettingTypeListItem $settingTypeListItem
     * @param User|null $user
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool $active
     *
     * @return UserSetting|null
     */
    public function store(
        SettingTypeListItem $settingTypeListItem,
        ?User $user,
        string $blockCode,
        string $settingCode,
        $value,
        bool $active
    ) : ?UserSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return UserSetting|null
     */
    public function updateValueByCodeDefault(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?UserSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return UserSetting|null
     */
    public function updateValueByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active
    ) : ?UserSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return UserSetting|null
     */
    public function updateOrCreateValueByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active
    ) : ?UserSetting;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     */
    public function deleteByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode
    ) : bool;
}
