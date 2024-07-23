<?php

namespace App\Services\Place\Interfaces;

use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;

/**
 * Interface RegionPlaceServiceInterface
 *
 * @package App\Services\Place\Interfaces
 */
interface RegionPlaceServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param CountryPlace $countryPlace
     * @param string $placeId
     * @param string|null $regionCode
     *
     * @return RegionPlace
     */
    public function getOrCreate(
        CountryPlace $countryPlace,
        string $placeId,
        ?string $regionCode
    ) : RegionPlace;
}
