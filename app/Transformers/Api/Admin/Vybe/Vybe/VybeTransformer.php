<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Transformers\Api\Admin\Vybe\ChangeRequest\VybeChangeRequestTransformer;
use App\Transformers\Api\Admin\Vybe\DeletionRequest\VybeDeletionRequestTransformer;
use App\Transformers\Api\Admin\Vybe\PublishRequest\VybePublishRequestTransformer;
use App\Transformers\Api\Admin\Vybe\UnsuspendRequest\VybeUnsuspendRequestTransformer;
use App\Transformers\Api\Admin\Vybe\UnpublishRequest\VybeUnpublishRequestTransformer;
use App\Transformers\Api\Admin\Vybe\Version\VybeVersionListTransformer;
use App\Transformers\Api\Admin\Vybe\Vybe\AppearanceCase\AppearanceCaseContainerTransformer;
use App\Transformers\Api\Admin\Vybe\Vybe\Schedule\ScheduleTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe
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
    protected ?EloquentCollection $platformIcons;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeTransformer constructor
     *
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $platformIcons
     */
    public function __construct(
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $platformIcons = null
    )
    {
        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection platformIcons */
        $this->platformIcons = $platformIcons;

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();

        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();

        /** @var VybeUnpublishRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();

        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'videos',
        'owner',
        'category',
        'subcategory',
        'activity',
        'type',
        'period',
        'access',
        'showcase',
        'order_accept',
        'age_limit',
        'step',
        'status',
        'devices',
        'schedules',
        'appearance_cases',
        'versions',
        'vybe_publish_request',
        'vybe_change_request',
        'vybe_unpublish_request',
        'vybe_unsuspend_request',
        'vybe_deletion_request'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        /** @var LanguageListItem $language */
        $language = $vybe->user->getLanguage();

        return [
            'id'                     => $vybe->id,
            'version'                => $vybe->version,
            'title'                  => $vybe->title,
            'user_count'             => $vybe->user_count,
            'images_ids'             => $vybe->images_ids,
            'videos_ids'             => $vybe->videos_ids,
            'order_advance'          => $vybe->order_advance,
            'access_password'        => $vybe->getDecryptedAccessPassword(),
            'category_suggestion'    => $vybe->category_suggestion ? [
                $language->iso => $vybe->category_suggestion
            ] : null,
            'subcategory_suggestion' => $vybe->subcategory_suggestion ? [
                $language->iso => $vybe->subcategory_suggestion
            ] : null,
            'activity_suggestion'    => $vybe->activity_suggestion ? [
                $language->iso => $vybe->activity_suggestion
            ] : null,
            'device_suggestion'      => $vybe->device_suggestion,
            'suspend_reason'         => $vybe->suspend_reason
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

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeCategory(Vybe $vybe) : ?Item
    {
        $category = $vybe->category;

        if (!$category) {
            if ($vybe->support) {
                $category = $vybe->support->category;
            }
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeSubcategory(Vybe $vybe) : ?Item
    {
        $subcategory = $vybe->subcategory;

        if (!$subcategory) {
            if ($vybe->support) {
                $subcategory = $vybe->support->subcategory;
            }
        }

        return $subcategory ? $this->item($subcategory, new CategoryTransformer) : null;
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
    public function includePeriod(Vybe $vybe) : ?Item
    {
        $period = $vybe->getPeriod();

        return $period ? $this->item($period, new VybePeriodTransformer) : null;
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
    public function includeShowcase(Vybe $vybe) : ?Item
    {
        $showcase = $vybe->getShowcase();

        return $showcase ? $this->item($showcase, new VybeShowcaseTransformer) : null;
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
    public function includeAgeLimit(Vybe $vybe) : ?Item
    {
        $vybeAgeLimit = $vybe->getAgeLimit();

        return $vybeAgeLimit ? $this->item($vybeAgeLimit, new VybeAgeLimitTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeStep(Vybe $vybe) : ?Item
    {
        $vybeStep = $vybe->getStep();

        return $vybeStep ? $this->item($vybeStep, new VybeStepTransformer) : null;
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
     * @return Item|null
     */
    public function includeAppearanceCases(Vybe $vybe) : ?Item
    {
        $appearanceCases = null;

        if ($vybe->relationLoaded('appearanceCases')) {
            $appearanceCases = $vybe->appearanceCases;
        }

        return $appearanceCases ?
            $this->item([], new AppearanceCaseContainerTransformer($appearanceCases, $this->platformIcons)) :
            null;
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
     * @return Collection|null
     */
    public function includeVersions(Vybe $vybe) : ?Collection
    {
        $versions = null;

        if ($vybe->relationLoaded('versions')) {
            $versions = $vybe->versions;
        }

        return $versions ? $this->collection($versions, new VybeVersionListTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybePublishRequest(Vybe $vybe) : ?Item
    {
        $publishRequest = null;

        if ($vybe->relationLoaded('publishRequest')) {
            if ($vybe->publishRequest) {
                if ($vybe->publishRequest->getRequestStatus()->isPending()) {
                    $publishRequest = $vybe->publishRequest;
                }
            }
        }

        return $publishRequest ? $this->item(
            $publishRequest,
            new VybePublishRequestTransformer(
                $this->vybeImageRepository->getByVybes(
                    new EloquentCollection([$publishRequest])
                ),
                $this->vybeVideoRepository->getByVybes(
                    new EloquentCollection([$publishRequest])
                )
            )
        ) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybeChangeRequest(Vybe $vybe) : ?Item
    {
        $changeRequest = null;

        if ($vybe->relationLoaded('changeRequest')) {
            if ($vybe->changeRequest) {
                if ($vybe->changeRequest->getRequestStatus()->isPending()) {
                    $changeRequest = $vybe->changeRequest;
                }
            }
        }

        return $changeRequest ? $this->item(
            $changeRequest,
            new VybeChangeRequestTransformer(
                $this->vybeImageRepository->getByVybes(
                    new EloquentCollection([$changeRequest])
                ),
                $this->vybeVideoRepository->getByVybes(
                    new EloquentCollection([$changeRequest])
                )
            )
        ) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeVybeUnpublishRequest(Vybe $vybe) : ?Item
    {
        $unpublishRequest = null;

        if ($vybe->relationLoaded('unpublishRequest')) {
            if ($vybe->unpublishRequest) {
                if ($vybe->unpublishRequest->getRequestStatus()->isPending()) {
                    $unpublishRequest = $vybe->unpublishRequest;
                }
            }
        }

        return $unpublishRequest ? $this->item($unpublishRequest, new VybeUnpublishRequestTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeVybeUnsuspendRequest(Vybe $vybe) : ?Item
    {
        $unsuspendRequest = null;

        if ($vybe->relationLoaded('unsuspendRequest')) {
            if ($vybe->unsuspendRequest) {
                if ($vybe->unsuspendRequest->getRequestStatus()->isPending()) {
                    $unsuspendRequest = $vybe->unsuspendRequest;
                }
            }
        }

        return $unsuspendRequest ? $this->item($unsuspendRequest, new VybeUnsuspendRequestTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeVybeDeletionRequest(Vybe $vybe) : ?Item
    {
        $deletionRequest = null;

        if ($vybe->relationLoaded('deletionRequest')) {
            if ($vybe->deletionRequest) {
                if ($vybe->deletionRequest->getRequestStatus()->isPending()) {
                    $deletionRequest = $vybe->deletionRequest;
                }
            }
        }

        return $deletionRequest ? $this->item($deletionRequest, new VybeDeletionRequestTransformer) : null;
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
