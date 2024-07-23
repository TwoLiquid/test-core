<?php

namespace App\Repositories\IpRegistrationList\Interfaces;

use App\Models\MySql\IpRegistrationList;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface IpRegistrationListRepositoryInterface
 *
 * @package App\Repositories\IpRegistrationList\Interfaces
 */
interface IpRegistrationListRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return IpRegistrationList|null
     */
    public function findById(
        ?int $id
    ) : ?IpRegistrationList;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param string|null $registrationDateFrom
     * @param string|null $registrationDateTo
     * @param string|null $ipAddress
     * @param string|null $username
     * @param string|null $name
     * @param array|null $statusesIds
     * @param string|null $location
     * @param bool|null $vpn
     * @param string|null $duplicates
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?string $registrationDateFrom,
        ?string $registrationDateTo,
        ?string $ipAddress,
        ?string $username,
        ?string $name,
        ?array $statusesIds,
        ?string $location,
        ?bool $vpn,
        ?string $duplicates
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param string|null $registrationDateFrom
     * @param string|null $registrationDateTo
     * @param string|null $ipAddress
     * @param string|null $username
     * @param string|null $name
     * @param array|null $statusesIds
     * @param string|null $location
     * @param bool|null $vpn
     * @param string|null $duplicates
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?string $registrationDateFrom,
        ?string $registrationDateTo,
        ?string $ipAddress,
        ?string $username,
        ?string $name,
        ?array $statusesIds,
        ?string $location,
        ?bool $vpn,
        ?string $duplicates,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkUserExistence(
        User $user
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param string $ipAddress
     * @param bool $vpn
     *
     * @return IpRegistrationList|null
     */
    public function store(
        User $user,
        string $ipAddress,
        bool $vpn
    ) : ?IpRegistrationList;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param IpRegistrationList $ipRegistrationList
     * @param User $user
     * @param string $ipAddress
     * @param bool $vpn
     *
     * @return IpRegistrationList
     */
    public function update(
        IpRegistrationList $ipRegistrationList,
        User $user,
        string $ipAddress,
        bool $vpn
    ) : IpRegistrationList;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param IpRegistrationList $ipRegistrationList
     *
     * @return bool
     */
    public function delete(
        IpRegistrationList $ipRegistrationList
    ) : bool;
}
