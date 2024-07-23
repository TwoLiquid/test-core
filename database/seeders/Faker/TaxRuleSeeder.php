<?php

namespace Database\Seeders\Faker;

use App\Exceptions\DatabaseException;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Repositories\TaxRule\TaxRuleCountryRepository;
use App\Repositories\TaxRule\TaxRuleRegionRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Class TaxRuleSeeder
 *
 * @package Database\Seeders\Faker
 */
class TaxRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        /** @var CountryPlaceRepository $countryPlaceRepository */
        $countryPlaceRepository = app(CountryPlaceRepository::class);

        /** @var RegionPlaceRepository $regionPlaceRepository */
        $regionPlaceRepository = app(RegionPlaceRepository::class);

        /** @var TaxRuleCountryRepository $taxRuleCountryRepository */
        $taxRuleCountryRepository = app(TaxRuleCountryRepository::class);

        /** @var TaxRuleRegionRepository $taxRuleRegionRepository */
        $taxRuleRegionRepository = app(TaxRuleRegionRepository::class);

        /**
         * Countries tax rules array
         */
        $countries = [
            [
                'country_place_id' => 'ChIJCzYy5IS16lQRQrfeQ5K5Oxw',
                'tax_rate'         => 19,
                'from_date'        => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        /**
         * Regions tax rules array
         */
        $regions = [
            [
                'country_place_id' => 'ChIJCzYy5IS16lQRQrfeQ5K5Oxw',
                'region_place_id'  => 'ChIJPV4oX_65j4ARVW8IJ6IJUYs',
                'tax_rate'         => 12,
                'from_date'        => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        foreach ($countries as $country) {

            /**
             * Getting country place
             */
            $countryPlace = $countryPlaceRepository->findByPlaceId(
                $country['country_place_id']
            );

            /**
             * Checking country place existence
             */
            if ($countryPlace) {

                /**
                 * Creating tax rule country
                 */
                $taxRuleCountry = $taxRuleCountryRepository->store(
                    $countryPlace,
                    $country['tax_rate'],
                    $country['from_date']
                );

                /**
                 * Checking tax rule country existence
                 */
                if ($taxRuleCountry) {
                    foreach ($regions as $region) {
                        if ($region['country_place_id'] == $taxRuleCountry->country_place_id) {

                            /**
                             * Getting region place
                             */
                            $regionPlace = $regionPlaceRepository->findByPlaceId(
                                $region['region_place_id']
                            );

                            /**
                             * Creating a tax rule region
                             */
                            $taxRuleRegionRepository->store(
                                $taxRuleCountry,
                                $regionPlace,
                                $region['tax_rate'],
                                $region['from_date']
                            );
                        }
                    }
                }
            }
        }
    }
}
