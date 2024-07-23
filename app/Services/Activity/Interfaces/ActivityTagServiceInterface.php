<?php

namespace App\Services\Activity\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ActivityTagServiceInterface
 *
 * @package App\Services\Activity\Interfaces
 */
interface ActivityTagServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param ActivityTag $activityTag
     * @param Collection $activities
     *
     * @return bool
     */
    public function checkRelatedActivities(
        ActivityTag $activityTag,
        Collection $activities
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param ActivityTag $activityTag
     * @param Activity $activity
     *
     * @return bool
     */
    public function checkRelatedActivity(
        ActivityTag $activityTag,
        Activity $activity
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection $activityTags
     *
     * @return Collection
     */
    public function getActivityTagsAllCategories(
        Collection $activityTags
    ) : Collection;
}