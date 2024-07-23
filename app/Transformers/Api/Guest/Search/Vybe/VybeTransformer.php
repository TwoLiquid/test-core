<?php

namespace App\Transformers\Api\Guest\Search\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Search\Vybe\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Services\Search\Vybe
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
        'period',
        'access',
        'showcase',
        'order_accept',
        'age_limit',
        'status',
        'devices',
        'schedules',
        'appearance_cases'
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
        $authUserFavoriteVybesIds = [];

        if ($this->user) {
            $authUserFavoriteVybesIds = $this->vybeRepository->getFavoritesIdsByUser(
                $this->user
            );
        }

        $vybePrice = null;
        $vybePriceUnitName = null;

        if ($vybe->relationLoaded('appearanceCases')) {

            /** @var AppearanceCase $cheapestAppearanceCase */
            $cheapestAppearanceCase = $vybe->appearanceCases
                ->sortBy([
                    'price'
                ])
                ->first();

            if ($cheapestAppearanceCase) {
                $vybePrice = $cheapestAppearanceCase->price;
                $vybePriceUnitName = $cheapestAppearanceCase->unit ? $cheapestAppearanceCase->unit->name : null;
            }
        }

        return [
            'id'                => $vybe->id,
            'version'           => $vybe->version,
            'title'             => $vybe->title,
            'user_count'        => $vybe->user_count,
            'rating'            => $vybe->rating,
            'rating_count'      => $vybe->rating_votes_count ? $vybe->rating_votes_count : null,
            'price'             => $vybePrice,
            'unit'              => $vybePriceUnitName,
            'liked'             => $authUserFavoriteVybesIds && in_array($vybe->id, $authUserFavoriteVybesIds),
            'images_ids'        => $vybe->images_ids,
            'videos_ids'        => $vybe->videos_ids,
            'order_advance'     => $vybe->order_advance,
            'suspend_reason'    => $vybe->suspend_reason
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
    public function includeOwner(Vybe $vybe): ?Item
    {
        $user = null;

        if ($vybe->relationLoaded('user')) {
            $user = $vybe->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeActivity(Vybe $vybe): ?Item
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
    public function includeType(Vybe $vybe): ?Item
    {
        $type = $vybe->getType();

        return $type ? $this->item($type, new VybeTypeTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includePeriod(Vybe $vybe): ?Item
    {
        $period = $vybe->getPeriod();

        return $period ? $this->item($period, new VybePeriodTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeAccess(Vybe $vybe): ?Item
    {
        $access = $vybe->getAccess();

        return $access ? $this->item($access, new VybeAccessTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeShowcase(Vybe $vybe): ?Item
    {
        $showcase = $vybe->getShowcase();

        return $showcase ? $this->item($showcase, new VybeShowcaseTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeOrderAccept(Vybe $vybe): ?Item
    {
        $orderAccept = $vybe->getOrderAccept();

        return $orderAccept ? $this->item($orderAccept, new VybeOrderAcceptTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeAgeLimit(Vybe $vybe): ?Item
    {
        $vybeAgeLimit = $vybe->getAgeLimit();

        return $vybeAgeLimit ? $this->item($vybeAgeLimit, new VybeAgeLimitTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeDevices(Vybe $vybe): ?Collection
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
    public function includeSchedules(Vybe $vybe): ?Collection
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
    public function includeAppearanceCases(Vybe $vybe): ?Collection
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
     * @return Item|null
     */
    public function includeStatus(Vybe $vybe): ?Item
    {
        $status = VybeStatusList::getItem(
            $vybe->status_id
        );

        return $status ? $this->item($status, new VybeStatusTransformer) : null;
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
