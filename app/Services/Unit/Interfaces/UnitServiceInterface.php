<?php

namespace App\Services\Unit\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UnitServiceInterface
 *
 * @package App\Services\Unit\Interfaces
 */
interface UnitServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Category $category
     *
     * @return Collection
     */
    public function getByCategory(
        Category $category
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param Activity $activity
     * @param array $unitsItems
     *
     * @return Collection
     */
    public function updateActivityPositions(
        Activity $activity,
        array $unitsItems
    ) : Collection;
}