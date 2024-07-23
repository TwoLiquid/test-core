<?php

namespace Tests\Feature\Integration\Environment;

use App\Microservices\Media\MediaMicroservice;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class MediaTest
 *
 * @package Tests\Feature\Integration\Environment
 */
class MediaTest extends TestCase
{
    /**
     * Checking media service http connection.
     *
     * @return void
     *
     * @throws GuzzleException
     */
    public function test_media_service_http_connection_check() : void
    {
        try {

            /**
             * Getting test data
             */
            (new MediaMicroservice())->test();
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
