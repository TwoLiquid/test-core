<?php

namespace App\Transformers\Api\Admin\Csau\Category;

use App\Models\MySql\Activity\Activity;
use App\Transformers\Api\Admin\Csau\Category\Activity\ActivityImageTransformer;
use App\Transformers\Api\Admin\Csau\Unit\UnitTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category
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
        'images',
        'units',
        'tags',
        'devices',
        'platforms'
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
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includeUnits(Activity $activity) : ?Collection
    {
        $units = null;

        if ($activity->relationLoaded('units')) {
            $units = $activity->units;
        }

        return $units ? $this->collection($units, new UnitTransformer) : null;
    }

    /**
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includeTags(Activity $activity) : ?Collection
    {
        $tags = null;

        if ($activity->relationLoaded('tags')) {
            $tags = $activity->tags;
        }

        return $tags ? $this->collection($tags, new ActivityTagTransformer) : null;
    }

    /**
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includeDevices(Activity $activity) : ?Collection
    {
        $devices = null;

        if ($activity->relationLoaded('devices')) {
            $devices = $activity->devices;
        }

        return $devices ? $this->collection($devices, new DeviceTransformer) : null;
    }

    /**
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includePlatforms(Activity $activity) : ?Collection
    {
        $platforms = null;

        if ($activity->relationLoaded('platforms')) {
            $platforms = $activity->platforms;
        }

        return $platforms ? $this->collection($platforms, new PlatformTransformer) : null;
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
