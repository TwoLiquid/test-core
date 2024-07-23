<?php

namespace App\Repositories\General\Interfaces;

use App\Models\MySql\GeneralSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface GeneralSettingRepositoryInterface
 *
 * @package App\Repositories\General\Interfaces
 */
interface GeneralSettingRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return GeneralSetting|null
     */
    public function findByCode(
        string $blockCode,
        string $settingCode
    ) : ?GeneralSetting;

    /**
     * This method provides finding rows
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     */
    public function getByCodes(
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
    public function existsByCode(
        string $blockCode,
        string $settingCode
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return GeneralSetting|null
     */
    public function store(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?GeneralSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return GeneralSetting|null
     */
    public function updateValueByCode(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?GeneralSetting;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     */
    public function deleteByCode(
        string $blockCode,
        string $settingCode
    ) : bool;
}
