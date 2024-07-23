<?php

namespace Tests\Feature\Integration\Environment;

use App\Microservices\Log\LogMicroservice;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class LogTest
 *
 * @package Tests\Feature\Integration\Environment
 */
class LogTest extends TestCase
{
    /**
     * Checking log service http connection.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_log_service_http_connection_check() : void
    {
        try {

            /**
             * Getting test data
             */
            (new LogMicroservice())->test();
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
