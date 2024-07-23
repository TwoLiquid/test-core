<?php

namespace Tests\Feature\Integration\Environment;

use App\Microservices\Auth\AuthMicroservice;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class AuthTest
 *
 * @package Tests\Feature\Integration\Environment
 */
class AuthTest extends TestCase
{
    /**
     * Checking auth service http connection.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_auth_service_http_connection_check() : void
    {
        try {

            /**
             * Getting test data
             */
            (new AuthMicroservice())->test();
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
