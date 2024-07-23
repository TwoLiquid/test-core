<?php

namespace App\Services\Unit;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Unit\UnitRepository;
use App\Services\Activity\ActivityService;
use App\Services\Unit\Interfaces\UnitServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UnitService
 *
 * @package App\Services\Unit
 */
class UnitService implements UnitServiceInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * UnitService constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();
    }

    /**
     * @param Category $category
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCategory(
        Category $category
    ) : Collection
    {
        /**
         * Getting activities
         */
        $activities = $this->activityService->getByCategory(
            $category
        );

        /**
         * Getting units by category and activities
         */
        return $this->unitRepository->getByCategory(
            $category,
            $activities
        );
    }

    /**
     * @param Activity $activity
     * @param array $unitsItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateActivityPositions(
        Activity $activity,
        array $unitsItems
    ) : Collection
    {
        /**
         * Updating activity units positions
         */
        return $this->activityRepository->updateUnitPositions(
            $activity,
            buildUnitsForSync($unitsItems)
        );
    }

    /**
     * @param Activity $activity
     * @param array $unitsItems
     *
     * @return bool
     */
    public function belongsToActivity(
        Activity $activity,
        array $unitsItems
    ) : bool
    {
        $unitsIds = $activity->units
            ->pluck('id')
            ->values()
            ->toArray();

        foreach ($unitsItems as $unitItem) {
            if (!in_array($unitItem['id'], $unitsIds)) {
                return false;
            }
        }

        return true;
    }
}