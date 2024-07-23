<?php

namespace App\Services\Place;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CityPlace;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Services\Google\GooglePlaceService;
use App\Services\Google\GoogleTimezoneService;
use App\Services\Google\GoogleTranslationService;
use App\Services\Place\Interfaces\CityPlaceServiceInterface;
use App\Services\Timezone\TimezoneService;
use Carbon\Carbon;
use Dedicated\GoogleTranslate\TranslateException;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class CityPlaceService
 *
 * @package App\Services\Place
 */
class CityPlaceService implements CityPlaceServiceInterface
{
    /**
     * @var CityPlaceRepository
     */
    protected CityPlaceRepository $cityPlaceRepository;

    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var GooglePlaceService
     */
    protected GooglePlaceService $googlePlaceService;

    /**
     * @var GoogleTimezoneService
     */
    protected GoogleTimezoneService $googleTimezoneService;

    /**
     * @var GoogleTranslationService
     */
    protected GoogleTranslationService $googleTranslationService;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * @var TimezoneService
     */
    protected TimezoneService $timezoneService;

    /**
     * CityPlaceService constructor
     */
    public function __construct()
    {
        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var GooglePlaceService googlePlaceService */
        $this->googlePlaceService = new GooglePlaceService();

        /** @var GoogleTimezoneService googleTimezoneService */
        $this->googleTimezoneService = new GoogleTimezoneService();

        /** @var GoogleTranslationService googleTranslationService */
        $this->googleTranslationService = new GoogleTranslationService();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();

        /** @var TimezoneService timezoneService */
        $this->timezoneService = new TimezoneService();
    }

    /**
     * @param string $placeId
     *
     * @return CityPlace
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function getOrCreate(
        string $placeId
    ) : CityPlace
    {
        /**
         * Getting city place
         */
        $cityPlace = $this->cityPlaceRepository->findByPlaceId(
            $placeId
        );

        /**
         * Checking city place existence
         */
        if (!$cityPlace) {

            /**
             * Getting city details
             */
            $details = $this->googlePlaceService->getDetails(
                $placeId
            );

            /**
             * Getting city coordinates
             */
            $coordinates = $this->getCoordinatesFromDetails(
                $details
            );

            /**
             * Getting city google timezone
             */
            $googleTimezoneResponse = $this->googleTimezoneService->getByCoordinates(
                $coordinates['lat'],
                $coordinates['lng'],
                Carbon::now()->timestamp
            );

            /**
             * Creating timezone
             */
            $timezone = $this->timezoneService->getOrCreate(
                $googleTimezoneResponse->timeZoneId,
                $coordinates['lat'],
                $coordinates['lng']
            );

            /**
             * Creating city place
             */
            $cityPlace = $this->cityPlaceRepository->store(
                $timezone,
                $placeId,
                $this->googleTranslationService->getTranslations(
                    $this->getCityNameFromDetails($details)
                ),
                $coordinates['lat'],
                $coordinates['lng']
            );
        }

        return $cityPlace;
    }

    /**
     * @param array $details
     *
     * @return string
     *
     * @throws BaseException
     */
    public function getCityNameFromDetails(
        array $details
    ) : string
    {
        foreach ($details['address_components'] as $addressComponent) {
            if (in_array('locality', $addressComponent['types'])) {
                return $addressComponent['long_name'];
            }
        }

        throw new BaseException(
            'Getting city name from google places api city details error.',
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
    public function getCoordinatesFromDetails(
        array $details
    ) : array
    {
        if (isset($details['geometry']['location'])) {
            return $details['geometry']['location'];
        }

        throw new BaseException(
            'Getting coordinates from google places api city details error.',
            null,
            500
        );
    }
}
