<?php

namespace Tests\Feature\Integration\System;

use App\Jobs\TestJob;
use Carbon\Carbon;
use RuntimeException;
use Tests\TestCase;
use Exception;

/**
 * Class RabbitMQTest
 *
 * @package Tests\Feature\Integration\System
 */
class RabbitMQTest extends TestCase
{
    /**
     * Testing rabbitmq job dispatching
     *
     * @return void
     */
    public function test_rabbitmq_test_job_dispatch_check() : void
    {
        try {

            /**
             * Sending a test job
             */
            TestJob::dispatch([
                'datetime' => Carbon::now()->format('d.m.y H:i:s'),
                'title'    => 'RabbitMQ test connection.',
                'url'      => config('app.url')
            ]);
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
