<?php

namespace App\Transformers\Api\Guest\Vybe\Calendar\Vybe;

use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\Guest\Vybe\Calendar\Vybe\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\Api\Guest\Vybe\Calendar\Vybe\Activity\ActivityTransformer;
use App\Transformers\Api\Guest\Vybe\Calendar\Vybe\User\UserTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Guest\Vybe\Calendar\Vybe
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $usersAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $platformsIcons;

    /**
     * VybeTransformer constructor
     *
     * @param EloquentCollection|null $activityImages
     * @param EloquentCollection|null $usersAvatars
     * @param EloquentCollection|null $platformsIcons
     */
    public function __construct(
        EloquentCollection $activityImages = null,
        EloquentCollection $usersAvatars = null,
        EloquentCollection $platformsIcons = null
    )
    {
        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection usersAvatars */
        $this->usersAvatars = $usersAvatars;

        /** @var EloquentCollection platformsIcons */
        $this->platformsIcons = $platformsIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'owner',
        'activity',
        'type',
        'appearance_cases'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'            => $vybe->id,
            'version'       => $vybe->version,
            'title'         => $vybe->title,
            'order_advance' => $vybe->order_advance,
            'users_count'   => $vybe->user_count
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeOwner(Vybe $vybe) : ?Item
    {
        $owner = null;

        if ($vybe->relationLoaded('user')) {
            $owner = $vybe->user;
        }

        return $owner ?
            $this->item(
                $owner,
                new UserTransformer($this->usersAvatars)
            ) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeActivity(Vybe $vybe) : ?Item
    {
        $activity = null;

        if ($vybe->relationLoaded('activity')) {
            $activity = $vybe->activity;
        }

        return $activity ?
            $this->item(
                $activity,
                new ActivityTransformer(
                    $this->activityImages
                )
            ) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeType(Vybe $vybe) : ?Item
    {
        $type = $vybe->getType();

        return $type ? $this->item($type, new VybeTypeTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeAppearanceCases(Vybe $vybe) : ?Collection
    {
        $appearanceCases = null;

        if ($vybe->relationLoaded('appearanceCases')) {
            $appearanceCases = $vybe->appearanceCases;
        }

        return $appearanceCases ?
            $this->collection(
                $appearanceCases,
                new AppearanceCaseTransformer($this->platformsIcons)
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
