<?php

namespace App\Transformers\Api\Guest\Profile\Home\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Dashboard\Vybe\Request\VybeChangeRequestTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\Request\VybeDeletionRequestTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\Request\VybePublishRequestTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\Request\VybeUnpublishRequestTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\Request\VybeUnsuspendRequestTransformer;
use App\Transformers\Api\Guest\Profile\Home\Vybe\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home\Vybe
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $user;

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
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * VybeTransformer constructor
     *
     * @param User|null $user
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        ?User $user = null,
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null,
        EloquentCollection $activityImages = null
    )
    {
        /** @var User user */
        $this->user = $user;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

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
        'schedules',
        'timeslot',
        'request'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     *
     * @throws DatabaseException
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
            'is_favorite'   => $this->user && $this->vybeRepository->isUserFavorite(
                    $vybe,
                    $this->user
                ),
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
        $owner = null;

        if ($vybe->relationLoaded('user')) {
            $owner = $vybe->user;
        }

        return $owner ? $this->item($owner, new UserTransformer) : null;
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

        return $activity ? $this->item(
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
     * @return Item|null
     */
    public function includeStatus(Vybe $vybe) : ?Item
    {
        $status = VybeStatusList::getItem(
            $vybe->status_id
        );

        return $status ? $this->item($status, new VybeStatusTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeOrderAccept(Vybe $vybe) : ?Item
    {
        $orderAccept = VybeOrderAcceptList::getItem(
            $vybe->order_accept_id
        );

        return $orderAccept ? $this->item($orderAccept, new OrderAcceptTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeTimeslot(Vybe $vybe) : ?Item
    {
        $timeslot = null;

        if (!$vybe->getType()->isSolo()) {
            if ($vybe->relationLoaded('orderItems')) {
                if (isset($vybe->orderItems[0])) {
                    if ($vybe->orderItems[0]->relationLoaded('timeslot')) {
                        $timeslot = $vybe->orderItems[0]->timeslot;
                    }
                }
            }
        }

        return $timeslot ? $this->item($timeslot, new TimeslotTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeRequest(Vybe $vybe) : ?Item
    {
        $request = $this->vybeService->getLatestPendingOrDeclinedRequest(
            $vybe
        );

        if ($request) {
            if (is_a($request, VybePublishRequest::class)) {
                return $this->item($request, new VybePublishRequestTransformer);
            }

            if (is_a($request, VybeChangeRequest::class)) {
                return $this->item($request, new VybeChangeRequestTransformer);
            }

            if (is_a($request, VybeUnpublishRequest::class)) {
                return $this->item($request, new VybeUnpublishRequestTransformer);
            }

            if (is_a($request, VybeUnsuspendRequest::class)) {
                return $this->item($request, new VybeUnsuspendRequestTransformer);
            }

            if (is_a($request, VybeDeletionRequest::class)) {
                return $this->item($request, new VybeDeletionRequestTransformer);
            }
        }

        return null;
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
