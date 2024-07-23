<?php

namespace Database\Seeders\Faker;

use App\Exceptions\DatabaseException;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Services\Google\GoogleTranslationService;
use Dedicated\GoogleTranslate\TranslateException;
use Illuminate\Database\Seeder;

/**
 * Class RegionPlaceSeeder
 *
 * @package Database\Seeders\Faker
 */
class RegionPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws DatabaseException
     * @throws TranslateException
     */
    public function run() : void
    {
        /** @var CountryPlaceRepository $countryPlaceRepository */
        $countryPlaceRepository = app(CountryPlaceRepository::class);

        /** @var RegionPlaceRepository $regionPlaceRepository */
        $regionPlaceRepository = app(RegionPlaceRepository::class);

        /** @var GoogleTranslationService $googleTranslationService */
        $googleTranslationService = app(GoogleTranslationService::class);

        /**
         * Regions array
         */
        $regions = [
            [
                'country_place_id' => 'ChIJCzYy5IS16lQRQrfeQ5K5Oxw',
                'place_id'         => 'ChIJPV4oX_65j4ARVW8IJ6IJUYs',
                'name'             => 'California',
                'code'             => 'CA'
            ]
        ];

        /** @var array $region */
        foreach ($regions as $region) {

            /**
             * Getting country
             */
            $countryPlace = $countryPlaceRepository->findByPlaceId(
                $region['country_place_id']
            );

            /**
             * Checking country existence
             */
            if ($countryPlace) {

                /**
                 * Creating region place
                 */
                $regionPlaceRepository->store(
                    $countryPlace,
                    $region['place_id'],
                    $googleTranslationService->getTranslations(
                        $region['name']
                    ),
                    $region['code']
                );
            }
        }
    }
}
