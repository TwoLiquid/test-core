<?php

namespace App\Transformers\Api\General\Dashboard\Vybe;

use App\Models\MySql\Vybe\Vybe;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Dashboard\Vybe\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\Schedule\ScheduleTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class FavoriteVybeTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe
 */
class FavoriteVybeTransformer extends BaseTransformer
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
    protected ?EloquentCollection $userAvatars;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * FavoriteVybeTransformer constructor
     *
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null,
        EloquentCollection $userAvatars = null
    )
    {
        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
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
        'access',
        'order_accept',
        'status',
        'devices',
        'appearance_cases',
        'schedules'
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
            'users_count'   => $vybe->user_count,
            'users_actual'  => $this->getUsersActual($vybe),
            'rating'        => $vybe->rating,
            'rating_count'  => $vybe->rating_votes_count,
            'live'          => false,
            'available_now' => false,
            'is_favorite'   => true,
            'images_ids'    => $vybe->images_ids,
            'videos_ids'    => $vybe->videos_ids
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
        $user = null;

        if ($vybe->relationLoaded('user')) {
            $user = $vybe->user;
        }

        return $user ? $this->item(
            $user,
            new UserTransformer(
                $this->userAvatars
            )
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

        return $activity ? $this->item($activity, new ActivityTransformer) : null;
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
     * @return Item|null
     */
    public function includeAccess(Vybe $vybe) : ?Item
    {
        $access = $vybe->getAccess();

        return $access ? $this->item($access, new VybeAccessTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeOrderAccept(Vybe $vybe) : ?Item
    {
        $orderAccept = $vybe->getOrderAccept();

        return $orderAccept ? $this->item($orderAccept, new VybeOrderAcceptTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeStatus(Vybe $vybe) : ?Item
    {
        $status = $vybe->getStatus();

        return $status ? $this->item($status, new VybeStatusTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeDevices(Vybe $vybe) : ?Collection
    {
        $devices = null;

        if ($vybe->relationLoaded('devices')) {
            $devices = $vybe->devices;
        }

        return $devices ? $this->collection($devices, new DeviceTransformer) : null;
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
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeSchedules(Vybe $vybe) : ?Collection
    {
        $schedules = null;

        if ($vybe->relationLoaded('schedules')) {
            $schedules = $vybe->schedules;
        }

        return $schedules ? $this->collection($schedules, new ScheduleTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return int|null
     */
    private function getUsersActual(Vybe $vybe) : ?int
    {
        $usersActual = 0;

        if (!$vybe->getType()->isSolo()) {
            if ($vybe->relationLoaded('orderItems')) {
                if (isset($vybe->orderItems[0])) {
                    if ($vybe->orderItems[0]->relationLoaded('timeslot')) {
                        $usersActual = $vybe->orderItems[0]
                            ->timeslot
                            ->users_count;
                    }
                }
            }
        }

        return $usersActual;
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
