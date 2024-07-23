<?php

namespace App\Settings\Aggregator\Interfaces;

/**
 * Interface GeneralSettingAggregatorInterface
 *
 * @package App\Settings\Aggregator\Interfaces
 */
interface GeneralSettingAggregatorInterface
{
    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getAdminMain() : array;
}