<?php

namespace App\Transformers\Api\Admin\Csau\Device\Activity;

use App\Models\MySql\Activity\Activity;
use App\Transformers\Api\Admin\Csau\Category\Activity\ActivityImageTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Device\Activity
 */
class ActivityTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * ActivityTransformer constructor
     *
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        EloquentCollection $activityImages = null
    )
    {
        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images'
    ];

    /**
     * @param Activity $activity
     *
     * @return array
     */
    public function transform(Activity $activity) : array
    {
        return [
            'id'          => $activity->id,
            'code'        => $activity->code,
            'name'        => $activity->getTranslations('name'),
            'visible'     => $activity->visible,
            'vybes_count' => $activity->vybes_count,
            'position'    => $activity->position
        ];
    }

    /**
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includeImages(Activity $activity) : ?Collection
    {
        $activityImage = $this->activityImages?->filter(function ($item) use ($activity) {
            return $item->activity_id == $activity->id;
        });

        return $activityImage ? $this->collection($activityImage, new ActivityImageTransformer) : null;
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
