<?php

namespace App\Services\Google;

use App\Exceptions\BaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Services\Google\Interfaces\GooglePlaceServiceInterface;
use Illuminate\Support\Collection;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\PlacesApi;
use Exception;

/**
 * Class GooglePlaceService
 *
 * @package App\Services\Google
 */
class GooglePlaceService implements GooglePlaceServiceInterface
{
    /**
     * @var PlacesApi
     */
    protected PlacesApi $placesApi;

    /**
     * GooglePlaceService constructor
     */
    public function __construct()
    {
        /** @var PlacesApi placesApi */
        $this->placesApi = new PlacesApi(
            config('google.places.key')
        );
    }

    /**
     * @param LanguageListItem $languageListItem
     * @param string $search
     *
     * @return Collection
     *
     * @throws BaseException
     */
    public function autocompleteCountry(
        LanguageListItem $languageListItem,
        string $search
    ) : Collection
    {
        try {
            $response = $this->placesApi->placeAutocomplete(trim($search), [
                'types'    => 'country',
                'language' => $languageListItem->iso
            ]);

            return $response['predictions'];
        } catch (GooglePlacesApiException $exception) {
            throw new BaseException(
                trans('exceptions/service/place.' . __FUNCTION__),
                $exception->getMessage(),
                500
            );
        }
    }

    /**
     * @param LanguageListItem $languageListItem
     * @param string $countryCode
     * @param string $search
     *
     * @return Collection
     *
     * @throws BaseException
     */
    public function autocompleteRegion(
        LanguageListItem $languageListItem,
        string $countryCode,
        string $search
    ) : Collection
    {
        try {
            $response = $this->placesApi->placeAutocomplete(trim($search), [
                'components' => 'country:' . strtolower($countryCode),
                'types'      => 'administrative_area_level_1',
                'language'   => $languageListItem->iso
            ]);

            return $response['predictions'];
        } catch (GooglePlacesApiException $exception) {
            throw new BaseException(
                trans('exceptions/service/place.' . __FUNCTION__),
                $exception->getMessage(),
                500
            );
        }
    }

    /**
     * @param LanguageListItem $languageListItem
     * @param string $search
     *
     * @return Collection
     *
     * @throws BaseException
     */
    public function autocompleteCity(
        LanguageListItem $languageListItem,
        string $search
    ) : Collection
    {
        try {
            $response = $this->placesApi->placeAutocomplete(trim($search), [
                'types'    => '(cities)',
                'language' => $languageListItem->iso
            ]);

            return $response['predictions'];
        } catch (GooglePlacesApiException $exception) {
            throw new BaseException(
                trans('exceptions/service/place.' . __FUNCTION__),
                $exception->getMessage(),
                500
            );
        }
    }

    /**
     * @param LanguageListItem $languageListItem
     * @param string $countryCode
     * @param string $search
     *
     * @return Collection
     *
     * @throws BaseException
     */
    public function autocompleteCityByCountry(
        LanguageListItem $languageListItem,
        string $countryCode,
        string $search
    ) : Collection
    {
        try {
            $response = $this->placesApi->placeAutocomplete(trim($search), [
                'components' => 'country:' . strtolower($countryCode),
                'types'      => '(cities)',
                'language'   => $languageListItem->iso
            ]);

            return $response['predictions'];
        } catch (GooglePlacesApiException $exception) {
            throw new BaseException(
                trans('exceptions/service/place.' . __FUNCTION__),
                $exception->getMessage(),
                500
            );
        }
    }

    /**
     * @param string $placeId
     *
     * @return array
     *
     * @throws BaseException
     */
    public function getDetails(
        string $placeId
    ) : array
    {
        try {
            $response = $this->placesApi->placeDetails(
                trim($placeId), [
                    'language' => LanguageList::getEnglish()->iso
                ]
            );

            return $response['result'];
        } catch (Exception $exception) {
            throw new BaseException(
                'Getting google place details error.',
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }

    /**
     * @param array $details
     *
     * @return string
     *
     * @throws BaseException
     */
    public function getCountryNameFromDetails(
        array $details
    ) : string
    {
        /** @var array $addressComponent */
        foreach ($details['address_components'] as $addressComponent) {
            if (in_array('country', $addressComponent['types'])) {
                return $addressComponent['long_name'];
            }
        }

        throw new BaseException(
            'Getting country name from google place details error.',
            null,
            500
        );
    }

    /**
     * @param array $details
     *
     * @return string
     *
     * @throws BaseException
     */
    public function getLocalityNameFromDetails(
        array $details
    ) : string
    {
        /** @var array $addressComponent */
        foreach ($details['address_components'] as $addressComponent) {
            if (in_array('locality', $addressComponent['types'])) {
                return $addressComponent['long_name'];
            }
        }

        throw new BaseException(
            'Getting locality name from google place details error.',
            null,
            500
        );
    }

    /**
     * @param array $details
     *
     * @return array
     *
     * @throws BaseException
     */
    public function getGeometryFromDetails(
        array $details
    ) : array
    {
        if (isset($details['geometry']['location'])) {
            return $details['geometry']['location'];
        }

        throw new BaseException(
            'Getting geometry from google place details error.',
            null,
            500
        );
    }

    /**
     * @param array $details
     *
     * @return string
     *
     * @throws BaseException
     */
    public function getRegionNameFromDetails(
        array $details
    ) : string
    {
        /** @var array $addressComponent */
        foreach ($details['address_components'] as $addressComponent) {
            if (in_array('administrative_area_level_1', $addressComponent['types'])) {
                return $addressComponent['long_name'];
            }
        }

        throw new BaseException(
            'Getting region name from google place details error.',
            null,
            500
        );
    }
}
