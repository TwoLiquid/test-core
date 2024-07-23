<?php

namespace Tests\Feature\Integration\Plugin\Google;

use App\Services\Google\Google2faService;
use Tests\TestCase;
use RuntimeException;
use Exception;

/**
 * Class AuthenticationTest
 *
 * @package Tests\Feature\Integration\Plugin\Google
 */
class AuthenticationTest extends TestCase
{
    /**
     * Testing google 2fa api qr code generating
     *
     * @return void
     */
    public function test_google_2fa_qr_code_generation_check() : void
    {
        try {

            /**
             * Getting qr core
             */
            (new Google2faService())->getQRCode(
                'Test',
                'test@test.com',
                (new Google2faService())->getSecretKey()
            );
        } catch (Exception $exception) {
            throw new RuntimeException(
                $exception->getMessage()
            );
        }

        $this->assertTrue(true);
    }
}
