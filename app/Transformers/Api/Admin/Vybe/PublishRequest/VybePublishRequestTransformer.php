<?php

namespace App\Transformers\Api\Admin\Vybe\PublishRequest;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Services\Vybe\VybePublishRequestService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybePublishRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\PublishRequest
 */
class VybePublishRequestTransformer extends BaseTransformer
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
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

    /**
     * @var VybePublishRequestService
     */
    protected VybePublishRequestService $vybePublishRequestService;

    /**
     * VybePublishRequestTransformer constructor
     *
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     */
    public function __construct(
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null
    )
    {
        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'videos',
        'vybe',
        'title_status',
        'category',
        'category_csau_status',
        'category_status',
        'subcategory',
        'subcategory_csau_status',
        'subcategory_status',
        'devices',
        'device_csau_status',
        'devices_status',
        'activity',
        'activity_csau_status',
        'activity_status',
        'period',
        'period_status',
        'type',
        'user_count_status',
        'appearance_cases',
        'appearance_cases_status',
        'schedules',
        'schedules_status',
        'order_advance_status',
        'access',
        'access_status',
        'showcase',
        'showcase_status',
        'order_accept',
        'order_accept_status',
        'age_limit',
        'status',
        'status_status',
        'toast_message_type',
        'request_status'
    ];

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return array
     */
    public function transform(VybePublishRequest $vybePublishRequest) : array
    {
        /** @var LanguageListItem $language */
        $language = $vybePublishRequest->vybe
            ->user
            ->getLanguage();

        return [
            'id'                       => $vybePublishRequest->_id,
            'title'                    => $vybePublishRequest->title,
            'category_suggestion'      => $vybePublishRequest->category_suggestion ? [
                $language->iso => $vybePublishRequest->category_suggestion
            ] : null,
            'subcategory_suggestion'   => $vybePublishRequest->subcategory_suggestion ? [
                $language->iso => $vybePublishRequest->subcategory_suggestion
            ] : null,
            'activity_suggestion'      => $vybePublishRequest->activity_suggestion ? [
                $language->iso => $vybePublishRequest->activity_suggestion
            ] : null,
            'device_suggestion'        => $vybePublishRequest->device_suggestion,
            'user_count'               => $vybePublishRequest->user_count,
            'order_advance'            => $vybePublishRequest->order_advance,
            'access_password'          => $vybePublishRequest->access_password,
            'previous_title'           => $vybePublishRequest->previous_title,
            'previous_category_id'     => $vybePublishRequest->previous_category_id,
            'previous_subcategory_id'  => $vybePublishRequest->previous_subcategory_id,
            'previous_activity_id'     => $vybePublishRequest->previous_activity_id,
            'previous_devices_ids'     => $vybePublishRequest->previous_devices_ids,
            'previous_period_id'       => $vybePublishRequest->previous_period_id,
            'previous_user_count'      => $vybePublishRequest->previous_user_count,
            'previous_type_id'         => $vybePublishRequest->previous_type_id,
            'previous_order_advance'   => $vybePublishRequest->previous_order_advance,
            'previous_access_id'       => $vybePublishRequest->previous_access_id,
            'previous_showcase_id'     => $vybePublishRequest->previous_showcase_id,
            'previous_order_accept_id' => $vybePublishRequest->previous_order_accept_id,
            'previous_status_id'       => $vybePublishRequest->previous_status_id,
            'toast_message_text'       => $vybePublishRequest->toast_message_text,
            'images_ids'               => $vybePublishRequest->images_ids,
            'videos_ids'               => $vybePublishRequest->videos_ids,
            'created_at'               => $vybePublishRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection|null
     */
    public function includeImages(VybePublishRequest $vybePublishRequest) : ?Collection
    {
        $vybeImages = $this->vybeImages?->filter(function ($item) use ($vybePublishRequest) {
            return !is_null($vybePublishRequest->images_ids) && in_array($item->id, $vybePublishRequest->images_ids);
        });

        return $vybeImages ? $this->collection($vybeImages, new VybeImageTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection|null
     */
    public function includeVideos(VybePublishRequest $vybePublishRequest) : ?Collection
    {
        $vybeVideos = $this->vybeVideos?->filter(function ($item) use ($vybePublishRequest) {
            return !is_null($vybePublishRequest->videos_ids) && in_array($item->id, $vybePublishRequest->videos_ids);
        });

        return $vybeVideos ? $this->collection($vybeVideos, new VybeVideoTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeVybe(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybe = null;

        if ($vybePublishRequest->relationLoaded('vybe')) {
            $vybe = $vybePublishRequest->vybe;
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeTitleStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $titleStatus = $vybePublishRequest->getTitleStatus();

        return $titleStatus ? $this->item($titleStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeCategory(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $category = null;

        if ($vybePublishRequest->relationLoaded('category')) {
            $category = $vybePublishRequest->category;
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeCategoryCsauStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $categoryCsauStatus = null;

        if ($vybePublishRequest->category_suggestion) {
            $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybePublishRequest(
                $vybePublishRequest
            );

            $categoryCsauStatus = $csauSuggestion?->getCategoryStatus();
        }

        return $categoryCsauStatus ? $this->item($categoryCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeCategoryStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $categoryStatus = $vybePublishRequest->getCategoryStatus();

        return $categoryStatus ? $this->item($categoryStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeSubcategory(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $subcategory = null;

        if ($vybePublishRequest->relationLoaded('subcategory')) {
            $subcategory = $vybePublishRequest->subcategory;
        }

        return $subcategory ? $this->item($subcategory, new CategoryTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeSubcategoryCsauStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $subcategoryCsauStatus = null;

        if ($vybePublishRequest->subcategory_suggestion) {
            $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybePublishRequest(
                $vybePublishRequest
            );

            $subcategoryCsauStatus = $csauSuggestion?->getSubcategoryStatus();
        }

        return $subcategoryCsauStatus ? $this->item($subcategoryCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeSubcategoryStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $subcategoryStatus = $vybePublishRequest->getSubcategoryStatus();

        return $subcategoryStatus ? $this->item($subcategoryStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeDevices(VybePublishRequest $vybePublishRequest) : ?Collection
    {
        $devices = null;

        if ($vybePublishRequest->devices_ids) {
            $devices = $this->deviceRepository->getByIds(
                $vybePublishRequest->devices_ids
            );
        }

        return $devices ? $this->collection($devices, new DeviceTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeDeviceCsauStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $deviceCsauStatus = null;

        if ($vybePublishRequest->device_suggestion) {
            $deviceSuggestion = $this->deviceSuggestionRepository->findForVybePublishRequest(
                $vybePublishRequest
            );

            $deviceCsauStatus = $deviceSuggestion?->getStatus();
        }

        return $deviceCsauStatus ? $this->item($deviceCsauStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeDevicesStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $devicesStatus = $vybePublishRequest->getDevicesStatus();

        return $devicesStatus ? $this->item($devicesStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeActivity(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $activity = null;

        if ($vybePublishRequest->relationLoaded('activity')) {
            $activity = $vybePublishRequest->activity;
        }

        return $activity ? $this->item($activity, new ActivityTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeActivityCsauStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $activityCsauStatus = null;

        if ($vybePublishRequest->activity_suggestion) {
            $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybePublishRequest(
                $vybePublishRequest
            );

            $activityCsauStatus = $csauSuggestion?->getActivityStatus();
        }

        return $activityCsauStatus ? $this->item($activityCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeActivityStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $activityStatus = $vybePublishRequest->getActivityStatus();

        return $activityStatus ? $this->item($activityStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includePeriod(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybePeriodListItem = $vybePublishRequest->getPeriod();

        return $vybePeriodListItem ? $this->item($vybePeriodListItem, new VybePeriodTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includePeriodStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $periodStatus = $vybePublishRequest->getPeriodStatus();

        return $periodStatus ? $this->item($periodStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeType(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeTypeListItem = $vybePublishRequest->getType();

        return $vybeTypeListItem ? $this->item($vybeTypeListItem, new VybeTypeTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeUserCountStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $userCountStatus = $vybePublishRequest->getUserCountStatus();

        return $userCountStatus ? $this->item($userCountStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeAppearanceCases(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $appearanceCases = null;

        if ($vybePublishRequest->relationLoaded('appearanceCases')) {
            $appearanceCases = $vybePublishRequest->appearanceCases;
        }

        return $appearanceCases ?
            $this->item([], new VybePublishRequestAppearanceCaseContainerTransformer($appearanceCases)) :
            null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeAppearanceCasesStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $appearanceCasesStatus = $this->vybePublishRequestService->getAppearanceCasesStatus(
            $vybePublishRequest
        );

        return $this->item($appearanceCasesStatus, new RequestFieldStatusTransformer);
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection|null
     */
    public function includeSchedules(VybePublishRequest $vybePublishRequest) : ?Collection
    {
        $schedules = null;

        if ($vybePublishRequest->relationLoaded('schedules')) {
            $schedules = $vybePublishRequest->schedules;
        }

        return $schedules ? $this->collection($schedules, new VybePublishRequestScheduleTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeSchedulesStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $schedulesStatus = $vybePublishRequest->getSchedulesStatus();

        return $schedulesStatus ? $this->item($schedulesStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeOrderAdvanceStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $orderAdvanceStatus = $vybePublishRequest->getOrderAdvanceStatus();

        return $orderAdvanceStatus ? $this->item($orderAdvanceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeAccess(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeAccessListItem = $vybePublishRequest->getAccess();

        return $vybeAccessListItem ? $this->item($vybeAccessListItem, new VybeAccessTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeAccessStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $accessStatus = $vybePublishRequest->getAccessStatus();

        return $accessStatus ? $this->item($accessStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeShowcase(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeShowcaseListItem = $vybePublishRequest->getShowcase();

        return $vybeShowcaseListItem ? $this->item($vybeShowcaseListItem, new VybeShowcaseTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeShowcaseStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $showcaseStatus = $vybePublishRequest->getShowcaseStatus();

        return $showcaseStatus ? $this->item($showcaseStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeOrderAccept(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeOrderAcceptListItem = $vybePublishRequest->getOrderAccept();

        return $vybeOrderAcceptListItem ? $this->item($vybeOrderAcceptListItem, new VybeOrderAcceptTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeOrderAcceptStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $orderAcceptStatus = $vybePublishRequest->getOrderAcceptStatus();

        return $orderAcceptStatus ? $this->item($orderAcceptStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeAgeLimit(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeAgeLimitListItem = $vybePublishRequest->getAgeLimit();

        return $vybeAgeLimitListItem ? $this->item($vybeAgeLimitListItem, new VybeAgeLimitTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeStatusListItem = $vybePublishRequest->getStatus();

        return $vybeStatusListItem ? $this->item($vybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeStatusStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $statusStatus = $vybePublishRequest->getStatusStatus();

        return $statusStatus ? $this->item($statusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $requestStatus = $vybePublishRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $toastMessageType = $vybePublishRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_publish_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_publish_requests';
    }
}
