<?php

namespace App\Transformers\Api\Admin\Csau\Category\Activity;

use App\Models\MySql\Activity\Activity;
use App\Transformers\BaseTransformer;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category\Activity
 */
class ActivityTransformer extends BaseTransformer
{
    /**
     * @param Activity $activity
     *
     * @return array
     */
    public function transform(Activity $activity) : array
    {
        return [
            'id'      => $activity->id,
            'code'    => $activity->code,
            'name'    => $activity->name,
            'visible' => $activity->visible
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'activity';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'activities';
    }
}
