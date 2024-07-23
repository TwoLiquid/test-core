<?php

namespace App\Repositories\Unit;

use App\Exceptions\DatabaseException;
use App\Lists\Unit\Type\UnitTypeList;
use App\Lists\Unit\Type\UnitTypeListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Models\MySql\Unit;
use App\Repositories\BaseRepository;
use App\Repositories\Unit\Interfaces\UnitRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UnitRepository
 *
 * @package App\Repositories\Unit
 */
class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    /**
     * UnitRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.unit.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Unit|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Unit
    {
        try {
            return Unit::query()
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Unit|null
     *
     * @throws DatabaseException
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?Unit
    {
        try {
            return Unit::query()
                ->with([
                    'vybes' => function ($query) {
                        $query->with([
                            'appearanceCases' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'appearance_id',
                                ]);
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'name'
                                ]);
                            }
                        ])
                        ->distinct();
                    }
                ])
                ->withCount([
                    'vybes'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return Unit|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?Unit
    {
        try {
            return Unit::query()
                ->where('name->en', '=', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
            return Unit::query()
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllEvent() : Collection
    {
        try {
            return Unit::query()
                ->where('type_id', '=', UnitTypeList::getEvent()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllNotEvent() : Collection
    {
        try {
            return Unit::query()
                ->withCount([
                    'vybes'
                ])
                ->where('type_id', '=', UnitTypeList::getUsual()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
            return Unit::query()
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
    public function getAllEventBySearch(
        string $search
    ) : Collection
    {
        try {
            return Unit::query()
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('type_id', '=', UnitTypeList::getEvent()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
    public function getAllNotEventBySearch(
        string $search
    ) : Collection
    {
        try {
            return Unit::query()
                ->withCount([
                    'vybes'
                ])
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('type_id', '=', UnitTypeList::getUsual()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
            return Unit::query()
                ->where('visible', '=', true)
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
    public function getAllEventPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Unit::query()
                ->where('type_id', '=', UnitTypeList::getEvent()->id)
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
    public function getAllNotEventPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Unit::query()
                ->withCount([
                    'vybes'
                ])
                ->where('type_id', '=', UnitTypeList::getUsual()->id)
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Unit::query()
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('visible', '=', true)
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
    public function getAllEventBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Unit::query()
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('type_id', '=', UnitTypeList::getEvent()->id)
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
    public function getAllNotEventBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Unit::query()
                ->withCount([
                    'vybes'
                ])
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('type_id', '=', UnitTypeList::getUsual()->id)
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
            return Unit::query()
                ->whereHas('activities', function ($query) use ($activity) {
                    $query->where('id', '=', $activity->id);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
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
            return Unit::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $activitiesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByActivitiesIds(
        array $activitiesIds
    ) : Collection
    {
        try {
            return Unit::query()
                ->whereHas('activities', function ($query) use ($activitiesIds) {
                    $query->whereIn('id', $activitiesIds);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param Collection $activities
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCategory(
        Category $category,
        Collection $activities
    ) : Collection
    {
        try {
            return Unit::query()
                ->whereHas('activities', function ($query) use ($activities) {
                    $query->whereIn('activity_id', $activities->pluck('id')
                        ->values()
                        ->toArray()
                    )->where('activities.visible', '=', true);
                })
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UnitTypeListItem $unitTypeListItem
     * @param array $name
     * @param int|null $duration
     * @param bool $visible
     *
     * @return Unit|null
     *
     * @throws DatabaseException
     */
    public function store(
        UnitTypeListItem $unitTypeListItem,
        array $name,
        ?int $duration,
        bool $visible = true
    ) : ?Unit
    {
        try {
            return Unit::query()->create([
                'type_id'  => $unitTypeListItem->id,
                'name'     => $name,
                'code'     => generateCodeByName($name['en']),
                'duration' => $duration,
                'visible'  => $visible
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Unit $unit
     * @param UnitTypeListItem|null $unitTypeListItem
     * @param array|null $name
     * @param int|null $duration
     * @param bool|null $visible
     *
     * @return Unit
     *
     * @throws DatabaseException
     */
    public function update(
        Unit $unit,
        ?UnitTypeListItem $unitTypeListItem,
        ?array $name,
        ?int $duration,
        ?bool $visible
    ) : Unit
    {
        try {
            $unit->update([
                'type_id'  => $unitTypeListItem ? $unitTypeListItem->id : $unit->type_id,
                'name'     => $name ?: $unit->name,
                'code'     => isset($name['en']) ? generateCodeByName($name['en']) : $unit->code,
                'duration' => $duration ?: $unit->duration,
                'visible'  => !is_null($visible) ? $visible : $unit->visible
            ]);

            return $unit;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Unit $unit
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Unit $unit
    ) : bool
    {
        try {
            return $unit->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/unit.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}