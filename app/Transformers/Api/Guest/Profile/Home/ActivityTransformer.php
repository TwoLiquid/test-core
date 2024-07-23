<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class ActivityTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * ActivityTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        User $user,
        EloquentCollection $activityImages = null
    )
    {
        /** @var User user */
        $this->user = $user;

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
            'name'        => $activity->name,
            'code'        => $activity->code,
            'vybes_count' => $this->user
                ->vybes
                ->where('activity_id', '=', $activity->id)
                ->count()
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
