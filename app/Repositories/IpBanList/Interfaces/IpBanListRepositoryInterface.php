<?php

namespace App\Repositories\IpBanList\Interfaces;

use App\Models\MySql\IpBanList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface IpBanListRepositoryInterface
 *
 * @package App\Repositories\IpBanList\Interfaces
 */
interface IpBanListRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return IpBanList|null
     */
    public function findById(
        ?int $id
    ) : ?IpBanList;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllBySearch(
        string $search
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param array $ipBlackListIds
     *
     * @return Collection
     */
    public function getByIds(
        array $ipBlackListIds
    ) : Collection;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param string $ipAddress
     *
     * @return bool
     */
    public function checkIpAddressExistence(
        string $ipAddress
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $ipAddress
     *
     * @return IpBanList|null
     */
    public function store(
        string $ipAddress
    ) : ?IpBanList;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $ipAddress
     *
     * @return IpBanList
     */
    public function storeIfNotExists(
        string $ipAddress
    ) : IpBanList;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param IpBanList $ipBanList
     * @param string $ipAddress
     *
     * @return IpBanList
     */
    public function update(
        IpBanList $ipBanList,
        string $ipAddress
    ) : IpBanList;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param IpBanList $ipBanList
     *
     * @return bool
     */
    public function delete(
        IpBanList $ipBanList
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param array $ipBanListIds
     *
     * @return bool
     */
    public function deleteByIds(
        array $ipBanListIds
    ) : bool;
}
