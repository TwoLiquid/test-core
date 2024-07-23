<?php

namespace App\Settings\General\Interfaces;

use App\Models\MySql\Timezone\Timezone;

/**
 * Interface TimezoneSettingInterface
 *
 * @package App\Settings\General\Interfaces
 */
interface TimezoneSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return Timezone|null
     */
    public function getTimezone() : ?Timezone;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     */
    public function setTimezone(
        int $value
    ) : void;
}
