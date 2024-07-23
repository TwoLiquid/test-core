<?php

namespace App\Settings\Vybe\Interfaces;

/**
 * Interface PendingPayoutDelaySettingInterface
 *
 * @package App\Settings\Vybe\Interfaces
 */
interface PendingHoursFromOrderSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getPendingHours() : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     */
    public function setPendingHours(
        int $value
    ) : void;
}