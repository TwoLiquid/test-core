<?php

namespace App\Services\Google\Interfaces;

use App\Models\MySql\Admin\AdminAuthProtection;

/**
 * Interface Google2faServiceInterface
 *
 * @package App\Services\Google\Interfaces
 */
interface Google2faServiceInterface
{
    /**
     * This method provides getting data
     *
     * @return string
     */
    public function getSecretKey() : string;

    /**
     * This method provides getting data
     *
     * @param string $companyName
     * @param string $email
     * @param string $secretKey
     *
     * @return string
     */
    public function getQRCode(
        string $companyName,
        string $email,
        string $secretKey
    ) : string;

    /**
     * This method provides executing data
     *
     * @param AdminAuthProtection $adminAuthProtection
     * @param string $otpPassword
     *
     * @return bool
     */
    public function verify(
        AdminAuthProtection $adminAuthProtection,
        string $otpPassword
    ) : bool;
}