<?php

namespace App\Transformers\Api\General\Auth\User;

use App\Models\MySql\Activity\Activity;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\General\Auth\User
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
        'category'
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
            'name'        => $activity->name,
            'code'        => $activity->code,
            'visible'     => $activity->visible,
            'vybes_count' => $activity->vybes_count ? $activity->vybes_count : 0
        ];
    }

    /**
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includeImages(Activity $activity) : ?Collection
    {
        $activityImages = $this->activityImages?->filter(function ($item) use ($activity) {
            return $item->activity_id == $activity->id;
        });

        return $activityImages ? $this->collection($activityImages, new ActivityImageTransformer) : null;
    }

    /**
     * @param Activity $activity
     *
     * @return Item|null
     */
    public function includeCategory(Activity $activity) : ?Item
    {
        $category = null;

        if ($activity->relationLoaded('category')) {
            $category = $activity->category;
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
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
