<?php

namespace Tests\Feature\Integration\Plugin;

use App\Exceptions\BaseException;
use App\Services\Bugsnag\BugsnagService;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Tests\TestCase;

/**
 * Class BugsnagTest
 *
 * @package Tests\Feature\Integration\Plugin
 */
class BugsnagTest extends TestCase
{
    /**
     * Testing bugsnag api connection.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_bugsnag_api_connection_check() : void
    {
        try {

            /**
             * Checking API integration
             */
            (new BugsnagService())->checkApiIntegration();
        } catch (BaseException $exception) {
            throw new RuntimeException(
                $exception->getHumanReadableMessage()
            );
        }

        $this->assertTrue(true);
    }
}
