<?php

namespace App\Transformers\Api\Guest\Catalog\Activity;

use App\Models\MySql\Activity\Activity;
use App\Transformers\Api\Guest\Catalog\Category\CategoryShortTransformer;
use App\Transformers\Api\Guest\Catalog\Vybe\AppearanceCase\UnitTransformer;
use App\Transformers\Api\Guest\Catalog\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\Activity
 */
class ActivityTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVideos;

    /**
     * ActivityTransformer constructor
     *
     * @param EloquentCollection|null $activityImages
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     */
    public function __construct(
        EloquentCollection $activityImages = null,
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null
    )
    {
        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'category',
        'vybes',
        'units'
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
            'vybes_count' => $activity->vybes_count ? $activity->vybes_count : 0,
            'visible'     => $activity->visible
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
     * @return Item|null
     */
    public function includeCategory(Activity $activity) : ?Item
    {
        $category = null;

        if ($activity->relationLoaded('category')) {
            $category = $activity->category;
        }

        return $category ? $this->item($category, new CategoryShortTransformer) : null;
    }

    /**
     * @param Activity $activity
     *
     * @return Collection|null
     */
    public function includeVybes(Activity $activity) : ?Collection
    {
        $vybes = null;

        if ($activity->relationLoaded('vybes')) {
            $vybes = $activity->vybes;
        }

        return $vybes ?
            $this->collection(
                $vybes,
                new VybeTransformer(
                    null,
                    $this->vybeImages,
                    $this->vybeVideos
                )
            ) : null;
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
