<?php

namespace App\Transformers\Api\Guest\Search;

use App\Transformers\Api\Guest\Search\Activity\ActivityTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class ActivitySearchTransformer
 *
 * @package App\Transformers\Api\Guest\Search
 */
class ActivitySearchTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activities;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * ActivitySearchTransformer constructor
     *
     * @param EloquentCollection|null $activities
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        ?EloquentCollection $activities = null,
        ?EloquentCollection $activityImages = null
    )
    {
        /** @var EloquentCollection activities */
        $this->activities = $activities;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'activities'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'activities_count' => $this->activities ? $this->activities->count() : 0
        ];
    }

    /**
     * @return Collection|null
     */
    public function includeActivities() : ?Collection
    {
        return $this->activities ?
            $this->collection(
                $this->activities,
                new ActivityTransformer(
                    $this->activityImages
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'activity_search';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'activity_searches';
    }
}
