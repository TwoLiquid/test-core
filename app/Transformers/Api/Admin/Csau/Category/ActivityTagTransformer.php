<?php

namespace App\Transformers\Api\Admin\Csau\Category;

use App\Models\MySql\Activity\ActivityTag;
use App\Transformers\BaseTransformer;

/**
 * Class ActivityTagTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category
 */
class ActivityTagTransformer extends BaseTransformer
{
    /**
     * @param ActivityTag $activityTag
     *
     * @return array
     */
    public function transform(ActivityTag $activityTag) : array
    {
        return [
            'id'               => $activityTag->id,
            'name'             => $activityTag->name,
            'code'             => $activityTag->code,
            'activities_count' => $activityTag->activities_count ? $activityTag->activities_count : 0
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'activity_tag';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'activity_tags';
    }
}
