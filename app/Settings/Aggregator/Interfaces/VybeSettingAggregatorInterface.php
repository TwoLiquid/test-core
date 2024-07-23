<?php

namespace App\Settings\Aggregator\Interfaces;

/**
 * Interface VybeSettingAggregatorInterface
 *
 * @package App\Settings\Aggregator\Interfaces
 */
interface VybeSettingAggregatorInterface
{
    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getAdminMain() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getAdminVybePage() : array;
}