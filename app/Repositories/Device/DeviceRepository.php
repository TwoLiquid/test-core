<?php

namespace App\Repositories\Device;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Device;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class DeviceRepository
 *
 * @package App\Repositories\Device
 */
class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
    /**
     * DeviceRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.device.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Device|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Device
    {
        try {
            return Device::query()
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Device|null
     *
     * @throws DatabaseException
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?Device
    {
        try {
            return Device::query()
                ->with([
                    'vybes' => function ($query) use ($id) {
                        $query->select([
                            'id',
                            'status_id',
                            'title',
                            'version',
                            'updated_at'
                        ])->with([
                            'appearanceCases' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'appearance_id',
                                ]);
                            }
                        ])->where('device_id', '=', $id);
                    },
                    'activities'
                ])
                ->withCount([
                    'vybes',
                    'activities'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return Device|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?Device
    {
        try {
            return Device::query()
                ->where('name', '=', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
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
            return Device::query()
                ->withCount([
                    'vybes'
                ])
                ->orderBy('name')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
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
            return Device::query()
                ->withCount([
                    'vybes'
                ])
                ->orderBy('name')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $devicesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $devicesIds
    ) : Collection
    {
        try {
            return Device::query()
                ->whereIn('id', $devicesIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByActivity(
        Activity $activity
    ) : Collection
    {
        try {
            return Device::query()
                ->whereHas('activities', function ($query) use ($activity) {
                    $query->where('activity_id', $activity->id);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     * @param bool|null $visible
     *
     * @return Device|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $name,
        ?bool $visible = true
    ) : ?Device
    {
        try {
            return Device::query()->create([
                'name'    => trim($name),
                'code'    => generateCodeByName(trim($name)),
                'visible' => $visible
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param string|null $name
     * @param bool|null $visible
     *
     * @return Device
     *
     * @throws DatabaseException
     */
    public function update(
        Device $device,
        ?string $name,
        ?bool $visible
    ) : Device
    {
        try {
            $device->update([
                'name'    => $name ? trim($name) : $device->name,
                'visible' => !is_null($visible) ? $visible : $device->visible
            ]);

            return $device;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function attachVybe(
        Device $device,
        Vybe $vybe
    ) : void
    {
        try {
            $device->vybes()->sync([
                $vybe->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param array $vybesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachVybes(
        Device $device,
        array $vybesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $device->vybes()->sync(
                $vybesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function detachVybe(
        Device $device,
        Vybe $vybe
    ) : void
    {
        try {
            $device->vybes()->detach([
                $vybe->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param array $vybesIds
     *
     * @throws DatabaseException
     */
    public function detachVybes(
        Device $device,
        array $vybesIds
    ) : void
    {
        try {
            $device->vybes()->detach(
                $vybesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function attachActivity(
        Device $device,
        Activity $activity
    ) : void
    {
        try {
            $device->activities()->sync([
                $activity->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param array $activitiesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachActivities(
        Device $device,
        array $activitiesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $device->activities()->sync(
                $activitiesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function detachActivity(
        Device $device,
        Activity $activity
    ) : void
    {
        try {
            $device->activities()->detach([
                $activity->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param array $activitiesIds
     *
     * @throws DatabaseException
     */
    public function detachActivities(
        Device $device,
        array $activitiesIds
    ) : void
    {
        try {
            $device->activities()->detach(
                $activitiesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Device $device
    ) : bool
    {
        try {
            return $device->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/device.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}