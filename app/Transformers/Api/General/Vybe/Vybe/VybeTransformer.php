<?php

namespace App\Transformers\Api\General\Vybe\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Transformers\Api\General\Vybe\ChangeRequest\VybeChangeRequestTransformer;
use App\Transformers\Api\General\Vybe\DeletionRequest\VybeDeletionRequestTransformer;
use App\Transformers\Api\General\Vybe\PublishRequest\VybePublishRequestTransformer;
use App\Transformers\Api\General\Vybe\UnpublishRequest\VybeUnpublishRequestTransformer;
use App\Transformers\Api\General\Vybe\UnsuspendRequest\VybeUnsuspendRequestTransformer;
use App\Transformers\Api\General\Vybe\Vybe\AppearanceCase\AppearanceCaseContainerTransformer;
use App\Transformers\Api\General\Vybe\Vybe\Schedule\ScheduleTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use ReflectionClass;
use ReflectionException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Vybe
 */
class VybeTransformer extends BaseTransformer
{
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
     * @var VybeImageRepository|null
     */
    protected ?VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository|null
     */
    protected ?VybeVideoRepository $vybeVideoRepository;

    /**
     * @var Vybe|null
     */
    protected ?Vybe $vybe;

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
     * @var VybePublishRequest|null
     */
    protected ?VybePublishRequest $vybePublishRequest = null;

    /**
     * @var VybeChangeRequest|null
     */
    protected ?VybeChangeRequest $vybeChangeRequest = null;

    /**
     * @var VybeUnpublishRequest|null
     */
    protected ?VybeUnpublishRequest $vybeUnpublishRequest = null;

    /**
     * @var VybeUnsuspendRequest|null
     */
    protected ?VybeUnsuspendRequest $vybeUnsuspendRequest = null;

    /**
     * @var VybeDeletionRequest|null
     */
    protected ?VybeDeletionRequest $vybeDeletionRequest = null;

    /**
     * VybeTransformer constructor
     *
     * @param Vybe|null $vybe
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $platformIcons
     *
     * @throws ReflectionException
     */
    public function __construct(
        ?Vybe $vybe = null,
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $platformIcons = null
    )
    {
        /** @var Vybe vybe */
        $this->vybe = $vybe;

        /**
         * Checking vybe existence
         */
        if ($this->vybe) {

            /**
             * Setting latest request
             */
            $this->getLatestRequest(
                $vybe
            );
        }

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
     * @param Vybe $vybe
     *
     * @throws ReflectionException
     */
    protected function getLatestRequest(
        Vybe $vybe
    ) : void
    {
        $requests = array_filter([
            $vybe->publishRequest,
            $vybe->changeRequest,
            $vybe->unpublishRequest,
            $vybe->unsuspendRequest,
            $vybe->deletionRequest
        ]);

        $result = null;
        foreach ($requests as $request) {
            if (!$result ||
                $request->created_at->gt($result->created_at)
            ) {
                $result = $request;
            }
        }

        if ($result) {
            $reflect = new ReflectionClass($result);

            if ($reflect->getShortName() == 'VybePublishRequest') {
                $this->vybePublishRequest = $result;
            }

            if ($reflect->getShortName() == 'VybeChangeRequest') {
                $this->vybeChangeRequest = $result;
            }

            if ($reflect->getShortName() == 'VybeUnpublishRequest') {
                $this->vybeUnpublishRequest = $result;
            }

            if ($reflect->getShortName() == 'VybeUnsuspendRequest') {
                $this->vybeUnsuspendRequest = $result;
            }

            if ($reflect->getShortName() == 'VybeDeletionRequest') {
                $this->vybeDeletionRequest = $result;
            }
        }
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
        return [
            'id'                     => $vybe->id,
            'version'                => $vybe->version,
            'title'                  => $vybe->title,
            'user_count'             => $vybe->user_count,
            'images_ids'             => $vybe->images_ids,
            'videos_ids'             => $vybe->videos_ids,
            'order_advance'          => $vybe->order_advance,
            'access_password'        => $vybe->getDecryptedAccessPassword(),
            'category_suggestion'    => $vybe->category_suggestion,
            'subcategory_suggestion' => $vybe->subcategory_suggestion,
            'activity_suggestion'    => $vybe->activity_suggestion,
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
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybePublishRequest() : ?Item
    {
        $publishRequest = null;

        if ($this->vybePublishRequest) {
            if ($this->vybePublishRequest->shown === false) {
                if ($this->vybePublishRequest->getRequestStatus()->isAccepted() ||
                    $this->vybePublishRequest->getRequestStatus()->isCanceled()
                ) {
                    $this->vybePublishRequestRepository->updateShown(
                        $this->vybePublishRequest,
                        true
                    );
                }

                $publishRequest = $this->vybePublishRequest;
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
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybeChangeRequest() : ?Item
    {
        $changeRequest = $this->vybeChangeRequest;

        if ($changeRequest) {
            if ($changeRequest->getRequestStatus()->isAccepted() ||
                $changeRequest->getRequestStatus()->isCanceled()
            ) {
                if ($changeRequest->shown === true) {
                    $changeRequest = null;
                } else {
                    $this->vybeChangeRequestRepository->updateShown(
                        $changeRequest,
                        true
                    );
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
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybeUnpublishRequest() : ?Item
    {
        $unpublishRequest = $this->vybeUnpublishRequest;

        if ($unpublishRequest) {
            if ($unpublishRequest->getRequestStatus()->isAccepted() ||
                $unpublishRequest->getRequestStatus()->isCanceled()
            ) {
                if ($unpublishRequest->shown === true) {
                    $unpublishRequest = null;
                } else {
                    $this->vybeUnpublishRequestRepository->updateShown(
                        $unpublishRequest,
                        true
                    );
                }
            }
        }

        return $unpublishRequest ? $this->item($unpublishRequest, new VybeUnpublishRequestTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybeUnsuspendRequest() : ?Item
    {
        $unsuspendRequest = $this->vybeUnsuspendRequest;

        if ($unsuspendRequest) {
            if ($unsuspendRequest->getRequestStatus()->isAccepted() ||
                $unsuspendRequest->getRequestStatus()->isCanceled()
            ) {
                if ($unsuspendRequest->shown === true) {
                    $unsuspendRequest = null;
                } else {
                    $this->vybeUnsuspendRequestRepository->updateShown(
                        $unsuspendRequest,
                        true
                    );
                }
            }
        }

        return $unsuspendRequest ? $this->item($unsuspendRequest, new VybeUnsuspendRequestTransformer) : null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeVybeDeletionRequest() : ?Item
    {
        $deletionRequest = $this->vybeDeletionRequest;

        if ($deletionRequest) {
            if ($deletionRequest->getRequestStatus()->isAccepted() ||
                $deletionRequest->getRequestStatus()->isCanceled()
            ) {
                if ($deletionRequest->shown === true) {
                    $deletionRequest = null;
                } else {
                    $this->vybeDeletionRequestRepository->updateShown(
                        $deletionRequest,
                        true
                    );
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
