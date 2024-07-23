<?php

namespace App\Services\IpBanList\Interfaces;

/**
 * Interface IpBanListServiceInterface
 *
 * @package App\Services\IpBanList\Interfaces
 */
interface IpBanListServiceInterface
{
    /**
     * Provides validation data
     *
     * @param string $idAddressesString
     *
     * @return bool|null
     */
    public function validateIpAddressesString(
        string $idAddressesString
    ) : ?bool;

    /**
     * Provides validation data
     * by related repository
     *
     * @param string $idAddressesString
     */
    public function storeIpAddresses(
        string $idAddressesString
    ) : void;

    /**
     * Provides checking data
     *
     * @param string $ipAddress
     *
     * @return bool
     */
    public function checkUserIpAddressExistence(
        string $ipAddress
    ) : bool;
}