<?php

namespace App\Services\Google\Interfaces;

use App\Lists\Language\LanguageListItem;
use Illuminate\Support\Collection;

/**
 * Interface GooglePlaceServiceInterface
 *
 * @package App\Services\Google\Interfaces
 */
interface GooglePlaceServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param LanguageListItem $languageListItem
     * @param string $search
     *
     * @return Collection
     */
    public function autocompleteCountry(
        LanguageListItem $languageListItem,
        string $search
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param LanguageListItem $languageListItem
     * @param string $countryCode
     * @param string $search
     *
     * @return Collection
     */
    public function autocompleteRegion(
        LanguageListItem $languageListItem,
        string $countryCode,
        string $search
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param LanguageListItem $languageListItem
     * @param string $search
     *
     * @return Collection
     */
    public function autocompleteCity(
        LanguageListItem $languageListItem,
        string $search
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param LanguageListItem $languageListItem
     * @param string $countryCode
     * @param string $search
     *
     * @return Collection
     */
    public function autocompleteCityByCountry(
        LanguageListItem $languageListItem,
        string $countryCode,
        string $search
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param string $placeId
     *
     * @return array
     */
    public function getDetails(
        string $placeId
    ) : array;

    /**
     * This method provides getting data
     *
     * @param array $details
     *
     * @return string
     */
    public function getCountryNameFromDetails(
        array $details
    ) : string;

    /**
     * This method provides getting data
     *
     * @param array $details
     *
     * @return string
     */
    public function getLocalityNameFromDetails(
        array $details
    ) : string;

    /**
     * This method provides getting data
     *
     * @param array $details
     *
     * @return array
     */
    public function getGeometryFromDetails(
        array $details
    ) : array;

    /**
     * This method provides getting data
     *
     * @param array $details
     *
     * @return string
     */
    public function getRegionNameFromDetails(
        array $details
    ) : string;
}
