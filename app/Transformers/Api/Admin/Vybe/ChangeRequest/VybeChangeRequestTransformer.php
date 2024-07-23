<?php

namespace App\Transformers\Api\Admin\Vybe\ChangeRequest;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Services\Vybe\VybeChangeRequestService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeChangeRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\ChangeRequest
 */
class VybeChangeRequestTransformer extends BaseTransformer
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
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * VybeChangeRequestTransformer constructor
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

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'videos',
        'title_status',
        'category',
        'previous_category',
        'category_csau_status',
        'category_status',
        'subcategory',
        'previous_subcategory',
        'subcategory_csau_status',
        'subcategory_status',
        'devices',
        'previous_devices',
        'device_csau_status',
        'devices_status',
        'activity',
        'previous_activity',
        'activity_csau_status',
        'activity_status',
        'period',
        'previous_period',
        'period_status',
        'type',
        'previous_type',
        'user_count_status',
        'appearance_cases',
        'appearance_cases_status',
        'schedules',
        'previous_schedules',
        'schedules_status',
        'order_advance_status',
        'access',
        'previous_access',
        'access_status',
        'showcase',
        'previous_showcase',
        'showcase_status',
        'order_accept',
        'previous_order_accept',
        'order_accept_status',
        'age_limit',
        'status',
        'previous_status',
        'status_status',
        'toast_message_type',
        'request_status'
    ];

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return array
     */
    public function transform(VybeChangeRequest $vybeChangeRequest) : array
    {
        /** @var LanguageListItem $language */
        $language = $vybeChangeRequest->vybe
            ->user
            ->getLanguage();

        return [
            'id'                       => $vybeChangeRequest->_id,
            'title'                    => $vybeChangeRequest->title,
            'previous_title'           => $vybeChangeRequest->previous_title,
            'title_status_id'          => $vybeChangeRequest->title_status_id,
            'title_status'             => null,
            'category_id'              => $vybeChangeRequest->category_id,
            'category'                 => null,
            'category_suggestion'      => $vybeChangeRequest->category_suggestion ? [
                $language->iso => $vybeChangeRequest->category_suggestion
            ] : null,
            'previous_category_id'     => $vybeChangeRequest->previous_category_id,
            'previous_category'        => null,
            'category_status_id'       => $vybeChangeRequest->category_status_id,
            'category_status'          => null,
            'subcategory_id'           => $vybeChangeRequest->subcategory_id,
            'subcategory'              => null,
            'subcategory_suggestion'   => $vybeChangeRequest->subcategory_suggestion ? [
                $language->iso => $vybeChangeRequest->subcategory_suggestion
            ] : null,
            'previous_subcategory_id'  => $vybeChangeRequest->previous_subcategory_id,
            'previous_subcategory'     => null,
            'subcategory_status_id'    => $vybeChangeRequest->subcategory_status_id,
            'subcategory_status'       => null,
            'activity_id'              => $vybeChangeRequest->activity_id,
            'activity'                 => null,
            'activity_suggestion'      => $vybeChangeRequest->activity_suggestion ? [
                $language->iso => $vybeChangeRequest->activity_suggestion
            ] : null,
            'previous_activity_id'     => $vybeChangeRequest->previous_activity_id,
            'previous_activity'        => null,
            'activity_status_id'       => $vybeChangeRequest->activity_status_id,
            'activity_status'          => null,
            'devices_ids'              => $vybeChangeRequest->devices_ids,
            'devices'                  => null,
            'previous_devices_ids'     => $vybeChangeRequest->previous_devices_ids,
            'previous_devices'         => null,
            'device_suggestion'        => $vybeChangeRequest->device_suggestion,
            'devices_status_id'        => $vybeChangeRequest->devices_status_id,
            'devices_status'           => null,
            'period_id'                => $vybeChangeRequest->period_id,
            'period'                   => null,
            'previous_period_id'       => $vybeChangeRequest->previous_period_id,
            'previous_period'          => null,
            'period_status_id'         => $vybeChangeRequest->period_status_id,
            'period_status'            => null,
            'user_count'               => $vybeChangeRequest->user_count,
            'previous_user_count'      => $vybeChangeRequest->previous_user_count,
            'user_count_status_id'     => $vybeChangeRequest->user_count_status_id,
            'user_count_status'        => null,
            'type_id'                  => $vybeChangeRequest->type_id,
            'type'                     => null,
            'previous_type_id'         => $vybeChangeRequest->previous_type_id,
            'previous_type'            => null,
            'order_advance'            => $vybeChangeRequest->order_advance,
            'previous_order_advance'   => $vybeChangeRequest->previous_order_advance,
            'order_advance_status_id'  => $vybeChangeRequest->order_advance_status_id,
            'order_advance_status'     => null,
            'images_ids'               => $vybeChangeRequest->images_ids,
            'images'                   => null,
            'previous_images_ids'      => $vybeChangeRequest->previous_images_ids,
            'previous_images'          => null,
            'videos_ids'               => $vybeChangeRequest->videos_ids,
            'videos'                   => null,
            'previous_videos_ids'      => $vybeChangeRequest->previous_videos_ids,
            'previous_videos'          => null,
            'access_id'                => $vybeChangeRequest->access_id,
            'access'                   => null,
            'previous_access_id'       => $vybeChangeRequest->previous_access_id,
            'previous_access'          => null,
            'access_status_id'         => $vybeChangeRequest->access_status_id,
            'access_status'            => null,
            'showcase_id'              => $vybeChangeRequest->showcase_id,
            'showcase'                 => null,
            'previous_showcase_id'     => $vybeChangeRequest->previous_showcase_id,
            'previous_showcase'        => null,
            'showcase_status_id'       => $vybeChangeRequest->showcase_status_id,
            'showcase_status'          => null,
            'order_accept_id'          => $vybeChangeRequest->order_accept_id,
            'order_accept'             => null,
            'previous_order_accept_id' => $vybeChangeRequest->previous_order_accept_id,
            'previous_order_accept'    => null,
            'order_accept_status_id'   => $vybeChangeRequest->order_accept_status_id,
            'order_accept_status'      => null,
            'status_id'                => $vybeChangeRequest->status_id,
            'status'                   => null,
            'previous_status_id'       => $vybeChangeRequest->previous_status_id,
            'previous_status'          => null,
            'status_status_id'         => $vybeChangeRequest->status_status_id,
            'status_status'            => null,
            'schedules_status_id'      => $vybeChangeRequest->schedules_status_id,
            'schedules_status'         => null,
            'toast_message_text'       => $vybeChangeRequest->toast_message_text,
            'created_at'               => $vybeChangeRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection|null
     */
    public function includeImages(VybeChangeRequest $vybeChangeRequest) : ?Collection
    {
        $vybeImages = $this->vybeImages?->filter(function ($item) use ($vybeChangeRequest) {
            return !is_null($vybeChangeRequest->images_ids) && in_array($item->id, $vybeChangeRequest->images_ids);
        });

        return $vybeImages ? $this->collection($vybeImages, new VybeImageTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection|null
     */
    public function includeVideos(VybeChangeRequest $vybeChangeRequest) : ?Collection
    {
        $vybeVideos = $this->vybeVideos?->filter(function ($item) use ($vybeChangeRequest) {
            return !is_null($vybeChangeRequest->videos_ids) && in_array($item->id, $vybeChangeRequest->videos_ids);
        });

        return $vybeVideos ? $this->collection($vybeVideos, new VybeVideoTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeVybe(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybe = null;

        if ($vybeChangeRequest->relationLoaded('vybe')) {
            $vybe = $vybeChangeRequest->vybe;
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeTitleStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $titleStatus = $vybeChangeRequest->getTitleStatus();

        return $titleStatus ? $this->item($titleStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeCategory(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $category = null;

        if ($vybeChangeRequest->relationLoaded('category')) {
            $category = $vybeChangeRequest->category;
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousCategory(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousCategory = null;

        if ($vybeChangeRequest->relationLoaded('previousCategory')) {
            $previousCategory = $vybeChangeRequest->previousCategory;
        }

        return $previousCategory ? $this->item($previousCategory, new CategoryTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeCategoryCsauStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $categoryCsauStatus = null;

        if ($vybeChangeRequest->category_suggestion) {
            $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybeChangeRequest(
                $vybeChangeRequest
            );

            $categoryCsauStatus = $csauSuggestion?->getCategoryStatus();
        }

        return $categoryCsauStatus ? $this->item($categoryCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeCategoryStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $categoryStatus = $vybeChangeRequest->getCategoryStatus();

        return $categoryStatus ? $this->item($categoryStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeSubcategory(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $subcategory = null;

        if ($vybeChangeRequest->relationLoaded('subcategory')) {
            $subcategory = $vybeChangeRequest->subcategory;
        }

        return $subcategory ? $this->item($subcategory, new CategoryTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousSubcategory(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousSubcategory = null;

        if ($vybeChangeRequest->relationLoaded('previousSubcategory')) {
            $previousSubcategory = $vybeChangeRequest->previousSubcategory;
        }

        return $previousSubcategory ? $this->item($previousSubcategory, new CategoryTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeSubcategoryCsauStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $subcategoryCsauStatus = null;

        if ($vybeChangeRequest->subcategory_suggestion) {
            $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybeChangeRequest(
                $vybeChangeRequest
            );

            $subcategoryCsauStatus = $csauSuggestion?->getSubcategoryStatus();
        }

        return $subcategoryCsauStatus ? $this->item($subcategoryCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeSubcategoryStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $subcategoryStatus = $vybeChangeRequest->getSubcategoryStatus();

        return $subcategoryStatus ? $this->item($subcategoryStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeDevices(VybeChangeRequest $vybeChangeRequest) : ?Collection
    {
        $devices = null;

        if ($vybeChangeRequest->devices_ids) {
            $devices = $this->deviceRepository->getByIds(
                $vybeChangeRequest->devices_ids
            );
        }

        return $devices ? $this->collection($devices, new DeviceTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includePreviousDevices(VybeChangeRequest $vybeChangeRequest) : ?Collection
    {
        $previousDevices = null;

        if ($vybeChangeRequest->previous_devices_ids) {
            $previousDevices = $this->deviceRepository->getByIds(
                $vybeChangeRequest->previous_devices_ids
            );
        }

        return $previousDevices ? $this->collection($previousDevices, new DeviceTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeDeviceCsauStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $deviceCsauStatus = null;

        if ($vybeChangeRequest->device_suggestion) {
            $deviceSuggestion = $this->deviceSuggestionRepository->findForVybeChangeRequest(
                $vybeChangeRequest
            );

            $deviceCsauStatus = $deviceSuggestion?->getStatus();
        }

        return $deviceCsauStatus ? $this->item($deviceCsauStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeDevicesStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $devicesStatus = $vybeChangeRequest->getDevicesStatus();

        return $devicesStatus ? $this->item($devicesStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeActivity(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $activity = null;

        if ($vybeChangeRequest->relationLoaded('activity')) {
            $activity = $vybeChangeRequest->activity;
        }

        return $activity ? $this->item($activity, new ActivityTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousActivity(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousActivity = null;

        if ($vybeChangeRequest->relationLoaded('previousActivity')) {
            $previousActivity = $vybeChangeRequest->previousActivity;
        }

        return $previousActivity ? $this->item($previousActivity, new ActivityTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeActivityCsauStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $activityCsauStatus = null;

        if ($vybeChangeRequest->activity_suggestion) {
            $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybeChangeRequest(
                $vybeChangeRequest
            );

            $activityCsauStatus = $csauSuggestion?->getActivityStatus();
        }

        return $activityCsauStatus ? $this->item($activityCsauStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeActivityStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $activityStatus = $vybeChangeRequest->getActivityStatus();

        return $activityStatus ? $this->item($activityStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePeriod(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybePeriodListItem = $vybeChangeRequest->getPeriod();

        return $vybePeriodListItem ? $this->item($vybePeriodListItem, new VybePeriodTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousPeriod(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousVybePeriodListItem = $vybeChangeRequest->getPreviousPeriod();

        return $previousVybePeriodListItem ? $this->item($previousVybePeriodListItem, new VybePeriodTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePeriodStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $periodStatus = $vybeChangeRequest->getPeriodStatus();

        return $periodStatus ? $this->item($periodStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeType(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeTypeListItem = $vybeChangeRequest->getType();

        return $vybeTypeListItem ? $this->item($vybeTypeListItem, new VybeTypeTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousType(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousVybeTypeListItem = $vybeChangeRequest->getPreviousType();

        return $previousVybeTypeListItem ? $this->item($previousVybeTypeListItem, new VybeTypeTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeUserCountStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $userCountStatus = $vybeChangeRequest->getUserCountStatus();

        return $userCountStatus ? $this->item($userCountStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeAppearanceCases(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $appearanceCases = null;

        if ($vybeChangeRequest->relationLoaded('appearanceCases')) {
            $appearanceCases = $vybeChangeRequest->appearanceCases;
        }

        return $appearanceCases ?
            $this->item([], new VybeChangeRequestAppearanceCaseContainerTransformer($appearanceCases)) :
            null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeAppearanceCasesStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $appearanceCasesStatus = $this->vybeChangeRequestService->getAppearanceCasesStatus(
            $vybeChangeRequest
        );

        return $this->item($appearanceCasesStatus, new RequestFieldStatusTransformer);
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection|null
     */
    public function includeSchedules(VybeChangeRequest $vybeChangeRequest) : ?Collection
    {
        $schedules = null;

        if ($vybeChangeRequest->relationLoaded('schedules')) {
            $schedules = $vybeChangeRequest->schedules;
        }

        return $schedules ? $this->collection($schedules, new VybeChangeRequestScheduleTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection|null
     */
    public function includePreviousSchedules(VybeChangeRequest $vybeChangeRequest) : ?Collection
    {
        $schedules = null;

        if ($vybeChangeRequest->getSchedulesStatus()) {
            $schedules = $vybeChangeRequest->vybe
                ->schedules;
        }

        return $schedules ? $this->collection($schedules, new ScheduleTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeSchedulesStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $schedulesStatus = $vybeChangeRequest->getSchedulesStatus();

        return $schedulesStatus ? $this->item($schedulesStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeOrderAdvanceStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $orderAdvanceStatus = $vybeChangeRequest->getOrderAdvanceStatus();

        return $orderAdvanceStatus ? $this->item($orderAdvanceStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeAccess(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeAccessListItem = $vybeChangeRequest->getAccess();

        return $vybeAccessListItem ? $this->item($vybeAccessListItem, new VybeAccessTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousAccess(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousVybeAccessListItem = $vybeChangeRequest->getPreviousAccess();

        return $previousVybeAccessListItem ? $this->item($previousVybeAccessListItem, new VybeAccessTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeAccessStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $accessStatus = $vybeChangeRequest->getAccessStatus();

        return $accessStatus ? $this->item($accessStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeShowcase(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeShowcaseListItem = $vybeChangeRequest->getShowcase();

        return $vybeShowcaseListItem ? $this->item($vybeShowcaseListItem, new VybeShowcaseTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousShowcase(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousVybeShowcaseListItem = $vybeChangeRequest->getPreviousShowcase();

        return $previousVybeShowcaseListItem ? $this->item($previousVybeShowcaseListItem, new VybeShowcaseTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeShowcaseStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $showcaseStatus = $vybeChangeRequest->getShowcaseStatus();

        return $showcaseStatus ? $this->item($showcaseStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeOrderAccept(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeOrderAcceptListItem = $vybeChangeRequest->getOrderAccept();

        return $vybeOrderAcceptListItem ? $this->item($vybeOrderAcceptListItem, new VybeOrderAcceptTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousOrderAccept(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousVybeOrderAcceptListItem = $vybeChangeRequest->getPreviousOrderAccept();

        return $previousVybeOrderAcceptListItem ? $this->item($previousVybeOrderAcceptListItem, new VybeOrderAcceptTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeOrderAcceptStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $orderAcceptStatus = $vybeChangeRequest->getOrderAcceptStatus();

        return $orderAcceptStatus ? $this->item($orderAcceptStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeAgeLimit(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeAgeLimitListItem = $vybeChangeRequest->getAgeLimit();

        return $vybeAgeLimitListItem ? $this->item($vybeAgeLimitListItem, new VybeAgeLimitTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeStatusListItem = $vybeChangeRequest->getStatus();

        return $vybeStatusListItem ? $this->item($vybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includePreviousStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $previousVybeStatusListItem = $vybeChangeRequest->getPreviousStatus();

        return $previousVybeStatusListItem ? $this->item($previousVybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeStatusStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $statusStatus = $vybeChangeRequest->getStatusStatus();

        return $statusStatus ? $this->item($statusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $requestStatus = $vybeChangeRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $toastMessageType = $vybeChangeRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_change_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_change_requests';
    }
}
