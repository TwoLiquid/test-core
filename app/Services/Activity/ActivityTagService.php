<?php

namespace App\Services\Activity;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Repositories\Activity\ActivityTagRepository;
use App\Services\Activity\Interfaces\ActivityTagServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ActivityTagService
 *
 * @package App\Services\Activity
 */
class ActivityTagService implements ActivityTagServiceInterface
{
    /**
     * @var ActivityTagRepository
     */
    protected ActivityTagRepository $activityTagRepository;

    /**
     * ActivityTagService constructor
     */
    public function __construct()
    {
        /** @var ActivityTagRepository activityTagRepository */
        $this->activityTagRepository = new ActivityTagRepository();
    }

    /**
     * @param ActivityTag $activityTag
     * @param Collection $activities
     * 
     * @return bool
     */
    public function checkRelatedActivities(
        ActivityTag $activityTag,
        Collection $activities
    ) : bool
    {
        /** @var Activity $activity */
        foreach ($activities as $activity) {
            if ($activity->category_id != $activityTag->category_id &&
                $activity->category_id != $activityTag->subcategory_id
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ActivityTag $activityTag
     * @param Activity $activity
     *
     * @return bool
     */
    public function checkRelatedActivity(
        ActivityTag $activityTag,
        Activity $activity
    ) : bool
    {
        if ($activity->category_id != $activityTag->category_id &&
            $activity->category_id != $activityTag->subcategory_id
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param Collection $activityTags
     *
     * @return Collection
     */
    public function getActivityTagsAllCategories(
        Collection $activityTags
    ) : Collection
    {
        $categories = new Collection();

        /** @var ActivityTag $activityTag */
        foreach ($activityTags as $activityTag) {
            if ($activityTag->relationLoaded('category')) {
                $categories->push(
                    $activityTag->category
                );
            }

            if ($activityTag->relationLoaded('subcategory')) {
                $categories->push(
                    $activityTag->subcategory
                );
            }
        }

        return $categories;
    }
}