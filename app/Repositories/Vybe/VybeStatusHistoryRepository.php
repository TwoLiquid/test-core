<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MongoDb\Vybe\VybeStatusHistory;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeStatusHistoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeStatusHistoryRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeStatusHistoryRepository extends BaseRepository implements VybeStatusHistoryRepositoryInterface
{
    /**
     * VybeStatusHistoryRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeStatusHistory.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeStatusHistory|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeStatusHistory
    {
        try {
            return VybeStatusHistory::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
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
            return VybeStatusHistory::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return VybeStatusHistory::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVybe(
        Vybe $vybe
    ) : Collection
    {
        try {
            return VybeStatusHistory::query()
                ->where('vybe_id', '=', $vybe->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeStatusListItem $vybeTypeListItem
     *
     * @return VybeStatusHistory|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        VybeStatusListItem $vybeTypeListItem
    ) : ?VybeStatusHistory
    {
        try {
            return VybeStatusHistory::query()->create([
                'vybe_id'   => $vybe->id,
                'status_id' => $vybeTypeListItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeStatusHistory $vybeStatusHistory
     * @param Vybe|null $vybe
     * @param VybeStatusListItem|null $vybeTypeListItem
     *
     * @return VybeStatusHistory
     *
     * @throws DatabaseException
     */
    public function update(
        VybeStatusHistory $vybeStatusHistory,
        ?Vybe $vybe,
        ?VybeStatusListItem $vybeTypeListItem
    ) : VybeStatusHistory
    {
        try {
            $vybeStatusHistory->update([
                'vybe_id'   => $vybe ? $vybe->id : $vybeStatusHistory->vybe_id,
                'status_id' => $vybeTypeListItem ? $vybeTypeListItem->id : $vybeStatusHistory->status_id
            ]);

            return $vybeStatusHistory;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeStatusHistory $vybeStatusHistory
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeStatusHistory $vybeStatusHistory
    ) : bool
    {
        try {
            return $vybeStatusHistory->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeStatusHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
