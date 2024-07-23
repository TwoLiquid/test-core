<?php

namespace App\Services\Place;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Repositories\Place\RegionPlaceRepository;
use App\Services\Google\GooglePlaceService;
use App\Services\Google\GoogleTranslationService;
use App\Services\Place\Interfaces\RegionPlaceServiceInterface;
use Dedicated\GoogleTranslate\TranslateException;

/**
 * Class RegionPlaceService
 *
 * @package App\Services\Place
 */
class RegionPlaceService implements RegionPlaceServiceInterface
{
    /**
     * @var GooglePlaceService
     */
    protected GooglePlaceService $googlePlaceService;

    /**
     * @var GoogleTranslationService
     */
    protected GoogleTranslationService $googleTranslationService;

    /**
     * @var RegionPlaceRepository
     */
    protected RegionPlaceRepository $regionPlaceRepository;

    /**
     * RegionPlaceService constructor
     */
    public function __construct()
    {
        /** @var GooglePlaceService googlePlaceService */
        $this->googlePlaceService = new GooglePlaceService();

        /** @var GoogleTranslationService googleTranslationService */
        $this->googleTranslationService = new GoogleTranslationService();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();
    }

    /**
     * @param CountryPlace $countryPlace
     * @param string $placeId
     * @param string|null $regionCode
     *
     * @return RegionPlace
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws TranslateException
     */
    public function getOrCreate(
        CountryPlace $countryPlace,
        string $placeId,
        ?string $regionCode = null
    ) : RegionPlace
    {
        /**
         * Getting region place
         */
        $regionPlace = $this->regionPlaceRepository->findByPlaceId(
            $placeId
        );

        /**
         * Checking region place existence
         */
        if (!$regionPlace) {

            /**
             * Getting google place region details
             */
            $details = $this->googlePlaceService->getDetails(
                $placeId
            );

            /**
             * Getting google place region name
             */
            $regionName = $this->googlePlaceService->getRegionNameFromDetails(
                $details
            );

            /**
             * Creating region place
             */
            return $this->regionPlaceRepository->store(
                $countryPlace,
                $placeId,
                $this->googleTranslationService->getTranslations(
                    $regionName
                ),
                $regionCode
            );
        }

        return $regionPlace;
    }
}
