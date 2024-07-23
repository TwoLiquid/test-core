<?php

namespace App\Transformers\Api\Guest\Catalog\ActivityTag;

use App\Models\MySql\Activity\ActivityTag;
use App\Transformers\BaseTransformer;

/**
 * Class ActivityTagListTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\ActivityTag
 */
class ActivityTagListTransformer extends BaseTransformer
{
    /**
     * @param ActivityTag $activityTag
     *
     * @return array
     */
    public function transform(ActivityTag $activityTag) : array
    {
        return [
            'id'                     => $activityTag->id,
            'name'                   => $activityTag->name,
            'code'                   => $activityTag->code,
            'visible_in_category'    => $activityTag->visible_in_category,
            'visible_in_subcategory' => $activityTag->visible_in_subcategory,
            'activities_count'       => $activityTag->activities_count
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
