<?php

namespace App\Settings\Aggregator\Interfaces;

/**
 * Interface RequestSettingAggregatorInterface
 *
 * @package App\Settings\Aggregator\Interfaces
 */
interface RequestSettingAggregatorInterface
{
    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getAdminMain() : array;
}