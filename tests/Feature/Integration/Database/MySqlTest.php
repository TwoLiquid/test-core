<?php

namespace Tests\Feature\Integration\Database;

use Exception;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

/**
 * Class MySqlTest
 *
 * @package Tests\Feature\Integration\Database
 */
class MySqlTest extends TestCase
{
    /**
     * Checking mysql core connection.
     *
     * @return void
     */
    public function test_mysql_core_connection_check() : void
    {
        try {

            /**
             * Getting mysql core pdo
             */
            DB::connection('mysql')->getPDO();
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }

    /**
     * Checking mysql media connection.
     *
     * @return void
     */
    public function test_mysql_media_connection_check() : void
    {
        try {

            /**
             * Getting mysql media pdo
             */
            DB::connection('mysql_media')->getPDO();
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
