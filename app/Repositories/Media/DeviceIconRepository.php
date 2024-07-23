<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Device;
use App\Models\MySql\Media\DeviceIcon;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\DeviceIconRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class DeviceIconRepository
 *
 * @package App\Repositories\Media
 */
class DeviceIconRepository extends BaseRepository implements DeviceIconRepositoryInterface
{
    /**
     * DeviceIconRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.deviceIcon.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return DeviceIcon|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?DeviceIcon
    {
        try {
            return DeviceIcon::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/deviceIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     *
     * @return DeviceIcon|null
     *
     * @throws DatabaseException
     */
    public function findByDevice(
        Device $device
    ) : ?DeviceIcon
    {
        try {
            return DeviceIcon::query()
                ->where('device_id', '=', $device->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/deviceIcon.' . __FUNCTION__),
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
            return DeviceIcon::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/deviceIcon.' . __FUNCTION__),
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
            return DeviceIcon::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/deviceIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $devices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByDevices(
        Collection $devices
    ) : Collection
    {
        try {
            return DeviceIcon::query()
                ->whereIn('device_id', $devices->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/deviceIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return DeviceIcon::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/deviceIcon.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}