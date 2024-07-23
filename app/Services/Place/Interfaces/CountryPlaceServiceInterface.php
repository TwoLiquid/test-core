<?php

namespace App\Services\Place\Interfaces;

use App\Models\MySql\Place\CountryPlace;

/**
 * Interface CountryPlaceServiceInterface
 *
 * @package App\Services\Place\Interfaces
 */
interface CountryPlaceServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param string $placeId
     * @param string|null $countryCode
     * @param string|null $name
     * @param string|null $officialName
     * @param bool|null $hasRegions
     * @param bool|null $excluded
     *
     * @return CountryPlace
     */
    public function create(
        string $placeId,
        ?string $countryCode,
        ?string $name,
        ?string $officialName,
        ?bool $hasRegions,
        ?bool $excluded
    ) : CountryPlace;
}
