<?php

namespace Tests\Feature\Integration\Plugin\Google;

use App\Exceptions\BaseException;
use App\Services\Google\GoogleRecaptchaService;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Tests\TestCase;

/**
 * Class RecaptchaTest
 *
 * @package Tests\Feature\Integration\Plugin\Google
 */
class RecaptchaTest extends TestCase
{
    /**
     * Testing a google recaptcha site key validness.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_google_recaptcha_site_key_check() : void
    {
        try {

            /**
             * Checking a google recaptcha site key
             */
            $response = (new GoogleRecaptchaService())->checkSiteKey();
        } catch (BaseException $exception) {
            throw new RuntimeException(
                $exception->getHumanReadableMessage()
            );
        }

        /**
         * Checking response
         */
        if ($response === false) {
            throw new RuntimeException(
                'Invalid site key.'
            );
        }

        $this->assertTrue(true);
    }

    /**
     * Testing a google recaptcha secret key validness.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_google_recaptcha_secret_key_check() : void
    {
        try {

            /**
             * Checking a google recaptcha secret key
             */
            $response = (new GoogleRecaptchaService())->checkSecretKey();
        } catch (BaseException $exception) {
            throw new RuntimeException(
                $exception->getHumanReadableMessage()
            );
        }

        /**
         * Checking response
         */
        if ($response === false) {
            throw new RuntimeException(
                'Invalid secret key.'
            );
        }

        $this->assertTrue(true);
    }
}
