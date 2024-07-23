<?php

namespace App\Services\Google\Interfaces;

use App\Support\Service\Google\Timezone\GoogleTimezoneResponse;

/**
 * Interface GoogleTimezoneServiceInterface
 *
 * @package App\Services\Google\Interfaces
 */
interface GoogleTimezoneServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $timestamp
     *
     * @return GoogleTimezoneResponse
     */
    public function getByCoordinates(
        float $latitude,
        float $longitude,
        string $timestamp
    ) : GoogleTimezoneResponse;

    /**
     * This method provides checking data
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $timestamp
     *
     * @return bool
     */
    public function checkDst(
        float $latitude,
        float $longitude,
        string $timestamp
    ) : bool;
}
