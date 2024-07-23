<?php

namespace App\Services\IpBanList;

use App\Exceptions\DatabaseException;
use App\Repositories\IpBanList\IpBanListRepository;
use App\Repositories\User\UserRepository;
use App\Services\IpBanList\Interfaces\IpBanListServiceInterface;

/**
 * Class IpBanListService
 *
 * @package App\Services\IpBanList
 */
class IpBanListService implements IpBanListServiceInterface
{
    /**
     * @var IpBanListRepository
     */
    protected IpBanListRepository $ipBanListRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * IpBanListService constructor
     */
    public function __construct()
    {
        /** @var IpBanListRepository ipBanListRepository */
        $this->ipBanListRepository = new IpBanListRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param string $idAddressesString
     *
     * @return bool|null
     */
    public function validateIpAddressesString(
        string $idAddressesString
    ) : ?bool
    {
        /** @var string $idAddress */
        foreach (parseIpAddressesString($idAddressesString) as $idAddress) {
            if (!filter_var($idAddress, FILTER_VALIDATE_IP)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $idAddressesString
     *
     * @throws DatabaseException
     */
    public function storeIpAddresses(
        string $idAddressesString
    ) : void
    {
        /** @var string $idAddress */
        foreach (parseIpAddressesString($idAddressesString) as $idAddress) {
            $this->ipBanListRepository->storeIfNotExists(
                $idAddress
            );
        }
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkUserIpAddressExistence(
        string $ipAddress
    ) : bool
    {
        if ($this->ipBanListRepository->checkIpAddressExistence(
            $ipAddress
        )) {
            return true;
        }

        return false;
    }
}