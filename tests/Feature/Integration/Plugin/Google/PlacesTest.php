<?php

namespace Tests\Feature\Integration\Plugin\Google;

use App\Lists\Language\LanguageList;
use App\Services\Google\GooglePlaceService;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class PlacesTest
 *
 * @package Tests\Feature\Integration\Plugin\Google
 */
class PlacesTest extends TestCase
{
    /**
     * Testing google places api autocomplete city check
     *
     * @return void
     */
    public function test_google_places_api_autocomplete_city_check() : void
    {
        try {

            /**
             * Getting autocomplete city
             */
            (new GooglePlaceService())->autocompleteCity(
                LanguageList::getEnglish(),
                'Moscow'
            );
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
