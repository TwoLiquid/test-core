<?php

namespace App\Transformers\Api\General\Vybe\Vybe;

use App\Models\MySql\Activity\Activity;
use App\Transformers\BaseTransformer;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Vybe
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
            'id'   => $activity->id,
            'name' => $activity->name,
            'code' => $activity->code
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
