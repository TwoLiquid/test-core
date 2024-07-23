<?php

namespace App\Repositories\Request\Interfaces;

use App\Models\MySql\RequestSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface RequestSettingRepositoryInterface
 *
 * @package App\Repositories\Request\Interfaces
 */
interface RequestSettingRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return RequestSetting|null
     */
    public function findByCode(
        string $blockCode,
        string $settingCode
    ) : ?RequestSetting;

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
     * @return RequestSetting|null
     */
    public function store(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?RequestSetting;

    /**
     * This method provides updating existing row
     * with an eloquent model by certain query
     *
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return RequestSetting|null
     */
    public function updateValueByCode(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?RequestSetting;

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
