<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface PendingPayoutDelayTipSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface PendingPayoutDelayTipSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getHours() : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setHours(
        int $value,
        ?bool $active
    ) : void;
}