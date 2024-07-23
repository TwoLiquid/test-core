<?php

namespace App\Services\Timezone\Interfaces;

use App\Models\MySql\Timezone\Timezone;
use App\Support\Service\Timezone\TimezoneOffsetResponse;

/**
 * Interface TimezoneServiceInterface
 *
 * @package App\Services\Timezone\Interfaces
 */
interface TimezoneServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param string $externalId
     * @param float $latitude
     * @param float $longitude
     *
     * @return Timezone
     */
    public function getOrCreate(
        string $externalId,
        float $latitude,
        float $longitude
    ) : Timezone;

    /**
     * This method provides getting data
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return TimezoneOffsetResponse
     */
    public function getOrCreateTimezoneOffsets(
        float $latitude,
        float $longitude
    ) : TimezoneOffsetResponse;

    /**
     * This method provides getting data
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return Timezone
     */
    public function getByCoordinates(
        float $latitude,
        float $longitude
    ) : Timezone;
}
