<?php

namespace App\Services\Google\Interfaces;

/**
 * Interface GoogleRecaptchaServiceInterface
 *
 * @package App\Services\Google\Interfaces
 */
interface GoogleRecaptchaServiceInterface
{
    /**
     * This method provides checking data
     *
     * @return bool
     */
    public function checkSiteKey() : bool;

    /**
     * This method provides checking data
     *
     * @return bool
     */
    public function checkSecretKey() : bool;
}
