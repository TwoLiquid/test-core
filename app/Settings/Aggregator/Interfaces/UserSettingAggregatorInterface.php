<?php

namespace App\Settings\Aggregator\Interfaces;

/**
 * Interface UserSettingAggregatorInterface
 *
 * @package App\Settings\Aggregator\Interfaces
 */
interface UserSettingAggregatorInterface
{
    /**
     * This method provides getting data
     *
     * @param bool $customOnly
     *
     * @return array
     */
    public function getBuyer(
        bool $customOnly
    ) : array;

    /**
     * This method provides getting data
     *
     * @param bool $customOnly
     *
     * @return array
     */
    public function getSeller(
        bool $customOnly
    ) : array;
}