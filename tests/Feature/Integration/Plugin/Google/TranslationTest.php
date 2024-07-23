<?php

namespace Tests\Feature\Integration\Plugin\Google;

use App\Services\Google\GoogleTranslationService;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class TranslationTest
 *
 * @package Tests\Feature\Integration\Plugin\Google
 */
class TranslationTest extends TestCase
{
    /**
     * Testing google translation api translations getting
     *
     * @return void
     */
    public function test_google_translation_api_get_translations_check() : void
    {
        try {

            /**
             * Getting test translations
             */
            (new GoogleTranslationService())->getTranslations('text');
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
