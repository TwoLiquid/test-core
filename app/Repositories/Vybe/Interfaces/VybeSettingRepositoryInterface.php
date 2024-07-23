<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Setting\Type\SettingTypeListItem;
use App\Models\MySql\Vybe\Vybe;
use App\Models\MySql\Vybe\VybeSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeSettingRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeSettingRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return VybeSetting|null
     */
    public function findByCodeDefault(
        string $blockCode,
        string $settingCode
    ) : ?VybeSetting;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return VybeSetting|null
     */
    public function findByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode
    ) : ?VybeSetting;

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
     * @param Vybe $vybe
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     */
    public function getByCodesCustom(
        Vybe $vybe,
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
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     */
    public function existsForVybeByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param SettingTypeListItem $settingTypeListItem
     * @param Vybe|null $vybe
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool $active
     *
     * @return VybeSetting|null
     */
    public function store(
        SettingTypeListItem $settingTypeListItem,
        ?Vybe $vybe,
        string $blockCode,
        string $settingCode,
        $value,
        bool $active
    ) : ?VybeSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return VybeSetting|null
     */
    public function updateValueByCodeDefault(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?VybeSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return VybeSetting|null
     */
    public function updateValueByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active
    ) : ?VybeSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return VybeSetting|null
     */
    public function updateOrCreateValueByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active
    ) : ?VybeSetting;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     */
    public function deleteByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode
    ) : bool;
}
