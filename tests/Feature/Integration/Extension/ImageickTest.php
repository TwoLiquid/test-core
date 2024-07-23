<?php

namespace Tests\Feature\Integration\Extension;

use RuntimeException;
use Tests\TestCase;

/**
 * Class ImageickTest
 *
 * @package Tests\Feature\Integration\Extension
 */
class ImageickTest extends TestCase
{
    /**
     * Checking php imageick extension is installed
     *
     * @return void
     */
    public function test_php_imageick_extension_installed_check() : void
    {
        /**
         * Checking php extension loaded
         */
        if (!extension_loaded('imagick')){
            throw new RuntimeException(
                'Imagick is not installed.'
            );
        }

        $this->assertTrue(true);
    }
}
