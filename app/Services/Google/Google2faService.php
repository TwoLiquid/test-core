<?php

namespace App\Services\Google;

use App\Exceptions\BaseException;
use App\Models\MySql\Admin\AdminAuthProtection;
use App\Services\Google\Interfaces\Google2faServiceInterface;
use PragmaRX\Google2FALaravel\Google2FA;
use Exception;

/**
 * Class Google2faService
 *
 * @package App\Services\Google
 */
class Google2faService implements Google2faServiceInterface
{
    /**
     * @var Google2FA
     */
    protected Google2FA $google2FA;

    /**
     * Google2faService constructor
     */
    public function __construct()
    {
        /** @var Google2FA google2FA */
        $this->google2FA = app(Google2FA::class);
    }

    /**
     * @return string
     *
     * @throws BaseException
     */
    public function getSecretKey() : string
    {
        try {

            /**
             * Returning generated Google 2FA secret key
             */
            return $this->google2FA->generateSecretKey();
        } catch (Exception $exception) {
            throw new BaseException(
                trans('exceptions/service/google2fa.' . __FUNCTION__),
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }

    /**
     * @param string $companyName
     * @param string $email
     * @param string $secretKey
     *
     * @return string
     *
     * @throws BaseException
     */
    public function getQRCode(
        string $companyName,
        string $email,
        string $secretKey
    ) : string
    {
        /**
         * Setting QR Code render type
         */
        $this->google2FA->setQRCodeBackend(
            'imagemagick'
        );

        try {

            /**
             * Returning generated QR code
             */
            return $this->google2FA->getQRCodeInline(
                $companyName,
                $email,
                $secretKey
            );
        } catch (Exception $exception) {
            throw new BaseException(
                trans('exceptions/service/google2fa.' . __FUNCTION__),
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }

    /**
     * @param AdminAuthProtection $adminAuthProtection
     * @param string $otpPassword
     *
     * @return bool
     */
    public function verify(
        AdminAuthProtection $adminAuthProtection,
        string $otpPassword
    ) : bool
    {
        /**
         * Returning Google 2FA verify result
         */
        return $this->google2FA->verifyGoogle2FA(
            $adminAuthProtection->secret,
            $otpPassword,
        );
    }
}
