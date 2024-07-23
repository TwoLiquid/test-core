<?php

namespace Tests\Feature\Integration\Database;

use Exception;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

/**
 * Class MongoDbTest
 *
 * @package Tests\Feature\Integration\Database
 */
class MongoDbTest extends TestCase
{
    /**
     * Checking mongodb core connection.
     *
     * @return void
     */
    public function test_mongodb_core_connection_check() : void
    {
        try {

            /**
             * Getting mongodb core pdo
             */
            DB::connection('mongodb')->getPDO();
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
