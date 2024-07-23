<?php

namespace Tests\Feature\Integration\Plugin;

use App\Exceptions\BaseException;
use App\Services\Payment\PayPalService;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;
use RuntimeException;

/**
 * Class PayPalTest
 *
 * @package Tests\Feature\Integration\Plugin
 */
class PayPalTest extends TestCase
{
    /**
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_paypal_api_auth_token_getting_check() : void
    {
        try {

            /**
             * Getting a paypal auth token
             */
            $this->assertTrue(
                (new PayPalService())->canGetAuthToken()
            );
        } catch (BaseException $exception) {
            throw new RuntimeException(
                $exception->getHumanReadableMessage()
            );
        }
    }
}
