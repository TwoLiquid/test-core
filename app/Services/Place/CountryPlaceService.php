<?php

namespace App\Services\Place;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\Place\CountryPlace;
use App\Repositories\Place\CountryPlaceRepository;
use App\Services\Google\GooglePlaceService;
use App\Services\Google\GoogleTranslationService;
use App\Services\Place\Interfaces\CountryPlaceServiceInterface;
use Dedicated\GoogleTranslate\TranslateException;

/**
 * Class CountryPlaceService
 *
 * @package App\Services\Place
 */
class CountryPlaceService implements CountryPlaceServiceInterface
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var GooglePlaceService
     */
    protected GooglePlaceService $googlePlaceService;

    /**
     * @var GoogleTranslationService
     */
    protected GoogleTranslationService $googleTranslationService;

    /**
     * CountryPlaceService constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var GooglePlaceService googlePlaceService */
        $this->googlePlaceService = new GooglePlaceService();

        /** @var GoogleTranslationService googleTranslationService */
        $this->googleTranslationService = new GoogleTranslationService();
    }

    /**
     * @param string $placeId
     * @param string|null $countryCode
     * @param string|null $name
     * @param string|null $officialName
     * @param bool|null $hasRegions
     * @param bool|null $excluded
     *
     * @return CountryPlace
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws TranslateException
     */
    public function create(
        string $placeId,
        ?string $countryCode = null,
        ?string $name = null,
        ?string $officialName = null,
        ?bool $hasRegions = false,
        ?bool $excluded = false
    ) : CountryPlace
    {
        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findByPlaceId(
            $placeId
        );

        /**
         * Checking country place existence
         */
        if (!$countryPlace) {

            /**
             * Getting country details
             */
            $details = $this->googlePlaceService->getDetails(
                $placeId
            );

            /**
             * Getting country coordinates
             */
            $coordinates = $this->googlePlaceService->getGeometryFromDetails(
                $details
            );

//            print_r(
//                $coordinates['lat'] . ', ' . $coordinates['lng']
//            );

            /**
             * Creating country place
             */
            $countryPlace = $this->countryPlaceRepository->store(
                $placeId,
                $countryCode ?: strtoupper($details['address_components'][0]['short_name']),
                $this->googleTranslationService->getTranslations(
                    $name ?: $details['address_components'][0]['long_name']
                ),
                $this->googleTranslationService->getTranslations(
                    $officialName ?: $details['address_components'][0]['long_name']
                ),
                $coordinates['lat'],
                $coordinates['lng'],
                $hasRegions,
                $excluded
            );
        }

        return $countryPlace;
    }
}
