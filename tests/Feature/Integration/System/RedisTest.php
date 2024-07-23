<?php

namespace Tests\Feature\Integration\System;

use Predis\Client;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class ConnectionTest
 *
 * @package Feature\Integration\System\Redis
 */
class RedisTest extends TestCase
{
    /**
     * Testing access to redis
     *
     * @return void
     */
    public function test_redis_access_check() : void
    {
        try {

            /**
             * Checking redis key existence
             */
            (new Client())->exists('test_key');
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
