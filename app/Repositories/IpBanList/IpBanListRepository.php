<?php

namespace App\Repositories\IpBanList;

use App\Exceptions\DatabaseException;
use App\Models\MySql\IpBanList;
use App\Repositories\BaseRepository;
use App\Repositories\IpBanList\Interfaces\IpBanListRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class IpBanListRepository
 *
 * @package App\Repositories\IpBanList
 */
class IpBanListRepository extends BaseRepository implements IpBanListRepositoryInterface
{
    /**
     * IpBanListRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.ipBanList.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return IpBanList|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?IpBanList
    {
        try {
            return IpBanList::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return IpBanList::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllBySearch(
        string $search
    ) : Collection
    {
        try {
            return IpBanList::query()
                ->whereRaw('lower(ip_address) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return IpBanList::query()
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return IpBanList::query()
                ->whereRaw('lower(ip_address) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ipBlackListIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ipBlackListIds
    ) : Collection
    {
        try {
            return IpBanList::query()
                ->whereIn('id', $ipBlackListIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
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
    public function checkIpAddressExistence(
        string $ipAddress
    ) : bool
    {
        try {
            return IpBanList::query()
                ->where('ip_address', '=', $ipAddress)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $ipAddress
     *
     * @return IpBanList|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $ipAddress
    ) : ?IpBanList
    {
        try {
            return IpBanList::query()->create([
                'ip_address' => trim($ipAddress)
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $ipAddress
     *
     * @return IpBanList
     *
     * @throws DatabaseException
     */
    public function storeIfNotExists(
        string $ipAddress
    ) : IpBanList
    {
        try {
            return IpBanList::query()->firstOrCreate([
                'ip_address' => trim($ipAddress)
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param IpBanList $ipBanList
     * @param string $ipAddress
     *
     * @return IpBanList
     *
     * @throws DatabaseException
     */
    public function update(
        IpBanList $ipBanList,
        string $ipAddress
    ) : IpBanList
    {
        try {
            $ipBanList->update([
                'ip_address' => $ipAddress ? trim($ipAddress) : $ipBanList->ip_address
            ]);

            return $ipBanList;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param IpBanList $ipBanList
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        IpBanList $ipBanList
    ) : bool
    {
        try {
            return $ipBanList->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ipBanListIds
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteByIds(
        array $ipBanListIds
    ) : bool
    {
        try {
            return IpBanList::query()
                ->whereIn('id', $ipBanListIds)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipBanList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
