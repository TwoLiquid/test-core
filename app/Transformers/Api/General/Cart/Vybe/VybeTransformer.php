<?php

namespace App\Transformers\Api\General\Cart\Vybe;

use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\Guest\Profile\Vybe\Vybe\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\Api\Guest\Vybe\Calendar\Vybe\Activity\ActivityTransformer;
use App\Transformers\Api\Guest\Vybe\Calendar\Vybe\User\UserTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\General\Cart\Vybe
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVideos;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * VybeTransformer constructor
     *
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null,
        EloquentCollection $activityImages = null,
        EloquentCollection $userAvatars = null
    )
    {
        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'videos',
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
            'id'          => $vybe->id,
            'version'     => $vybe->version,
            'title'       => $vybe->title,
            'users_count' => $vybe->user_count
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeImages(Vybe $vybe) : ?Collection
    {
        $vybeImages = $this->vybeImages?->filter(function ($item) use ($vybe) {
            return !is_null($vybe->images_ids) && in_array($item->id, $vybe->images_ids);
        });

        return $vybeImages ? $this->collection($vybeImages, new VybeImageTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeVideos(Vybe $vybe) : ?Collection
    {
        $vybeVideos = $this->vybeVideos?->filter(function ($item) use ($vybe) {
            return !is_null($vybe->videos_ids) && in_array($item->id, $vybe->videos_ids);
        });

        return $vybeVideos ? $this->collection($vybeVideos, new VybeVideoTransformer) : null;
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
                new UserTransformer($this->userAvatars)
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

        return $appearanceCases ? $this->collection($appearanceCases, new AppearanceCaseTransformer) : null;
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
