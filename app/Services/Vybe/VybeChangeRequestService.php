<?php

namespace App\Services\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Microservices\Media\Responses\VybeImageResponse;
use App\Microservices\Media\Responses\VybeVideoResponse;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\Unit\UnitRepository;
use App\Repositories\Vybe\VybeChangeRequestAppearanceCaseRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeChangeRequestScheduleRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Place\CityPlaceService;
use App\Services\Suggestion\CsauSuggestionService;
use App\Services\Vybe\Interfaces\VybeChangeRequestServiceInterface;
use Dedicated\GoogleTranslate\TranslateException;
use Illuminate\Database\Eloquent\Collection;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class VybeChangeRequestService
 *
 * @package App\Services\Vybe
 */
class VybeChangeRequestService implements VybeChangeRequestServiceInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CityPlaceRepository
     */
    protected CityPlaceRepository $cityPlaceRepository;

    /**
     * @var CityPlaceService
     */
    protected CityPlaceService $cityPlaceService;

    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var CsauSuggestionService
     */
    protected CsauSuggestionService $csauSuggestionService;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * @var VybeChangeRequestAppearanceCaseRepository
     */
    protected VybeChangeRequestAppearanceCaseRepository $vybeChangeRequestAppearanceCaseRepository;

    /**
     * @var VybeChangeRequestScheduleRepository
     */
    protected VybeChangeRequestScheduleRepository $vybeChangeRequestScheduleRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * VybeChangeRequestService constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var CityPlaceService cityPlaceService */
        $this->cityPlaceService = new CityPlaceService();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var CsauSuggestionService csauSuggestionService */
        $this->csauSuggestionService = new CsauSuggestionService();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();

        /** @var VybeChangeRequestAppearanceCaseRepository vybeChangeRequestAppearanceCaseRepository */
        $this->vybeChangeRequestAppearanceCaseRepository = new VybeChangeRequestAppearanceCaseRepository();

        /** @var VybeChangeRequestScheduleRepository vybeChangeRequestScheduleRepository */
        $this->vybeChangeRequestScheduleRepository = new VybeChangeRequestScheduleRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function checkVybeAvailability(
        Vybe $vybe
    ) : bool
    {
        /**
         * Checking vybe status
         */
        if ($vybe->getStatus()->isPublished() ||
            $vybe->getStatus()->isPaused()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Vybe $vybe
     * @param array $data
     * @param array|null $changedImagesIds
     * @param array|null $changedVideosIds
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function hasVybeChangeRequestChanges(
        Vybe $vybe,
        array $data,
        ?array $changedImagesIds,
        ?array $changedVideosIds
    ) : bool
    {
        /**
         * Preparing changed vybe title variable
         */
        $changedTitle = null;

        /**
         * Check vybe title changes
         */
        if (isset($data['title'])) {
            if (strcmp($vybe->title, $data['title']) !== 0) {

                /**
                 * Getting changed vybe title
                 */
                $changedTitle = $data['title'];
            }
        }

        /**
         * Preparing changed vybe category variable
         */
        $changedCategory = null;

        /**
         * Checking vybe category changes
         */
        if (isset($data['category_id'])) {
            if ($vybe->category) {
                if ($vybe->category->id != $data['category_id']) {

                    /**
                     * Getting changed vybe category
                     */
                    $changedCategory = $this->categoryRepository->findById(
                        $data['category_id']
                    );
                }
            }
        }

        /**
         * Checking vybe category suggestion changes
         */
        if (isset($data['category_suggestion'])) {

            /**
             * Getting changed vybe category suggestion
             */
            $changedCategory = $data['category_suggestion'];
        }

        /**
         * Preparing changed vybe subcategory variable
         */
        $changedSubcategory = null;

        /**
         * Checking vybe subcategory changes
         */
        if (isset($data['subcategory_id'])) {
            if ($vybe->subcategory) {
                if ($vybe->subcategory->id != $data['subcategory_id']) {

                    /**
                     * Getting changed vybe subcategory
                     */
                    $changedSubcategory = $this->categoryRepository->findById(
                        $data['subcategory_id']
                    );
                }
            }
        }

        /**
         * Checking vybe subcategory suggestion changes
         */
        if (isset($data['subcategory_suggestion'])) {

            /**
             * Getting changed vybe subcategory suggestion
             */
            $changedSubcategory = $data['subcategory_suggestion'];
        }

        /**
         * Preparing changed vybe activity variable
         */
        $changedActivity = null;

        /**
         * Checking vybe activity changes
         */
        if (isset($data['activity_id'])) {
            if ($vybe->activity) {
                if ($vybe->activity->id != $data['activity_id']) {

                    /**
                     * Getting changed vybe activity
                     */
                    $changedActivity = $this->activityRepository->findById(
                        $data['activity_id']
                    );
                }
            }
        }

        /**
         * Checking vybe activity suggestion changes
         */
        if (isset($data['activity_suggestion'])) {

            /**
             * Getting changed vybe activity suggestion
             */
            $changedActivity = $data['activity_suggestion'];
        }

        /**
         * Preparing changed vybe devices ids variable
         */
        $changedDevicesIds = null;

        /**
         * Checking vybe devices changes
         */
        if (!compareTwoArrays(
            $vybe->devices ? $vybe->devices->pluck('id')->toArray() : null,
            $data['devices_ids']
        )) {

            /**
             * Getting changed vybe devices ids
             */
            $changedDevicesIds = $data['devices_ids'];
        }

        /**
         * Checking vybe device suggestion changes
         */
        if (isset($data['device_suggestion'])) {

            /**
             * Getting changed vybe device suggestion
             */
            $changedDevicesIds = $data['device_suggestion'];
        }

        /**
         * Preparing changed vybe period variable
         */
        $changedVybePeriod = null;

        /**
         * Checking vybe period
         */
        if (isset($data['period_id'])) {
            if ($vybe->getPeriod()->id != $data['period_id']) {

                /**
                 * Getting changed vybe period
                 */
                $changedVybePeriod = VybePeriodList::getItem(
                    $data['period_id']
                );
            }
        }

        /**
         * Preparing changed vybe user count variable
         */
        $changedUserCount = null;

        /**
         * Checking vybe user count
         */
        if (isset($data['user_count'])) {
            if ($vybe->user_count != $data['user_count']) {

                /**
                 * Getting changed vybe user count
                 */
                $changedUserCount = $data['user_count'];
            }
        }

        /**
         * Preparing changed vybe order advance variable
         */
        $changedOrderAdvance = null;

        /**
         * Checking vybe order advance
         */
        if (isset($data['order_advance'])) {
            if ($vybe->order_advance != $data['order_advance']) {

                /**
                 * Getting changed vybe order advance
                 */
                $changedOrderAdvance = $data['order_advance'];
            }
        }

        /**
         * Preparing changed vybe access variable
         */
        $changedVybeAccess = null;

        /**
         * Checking vybe access
         */
        if (isset($data['access_id'])) {
            if ($vybe->getAccess()->id != $data['access_id']) {

                /**
                 * Getting changed vybe access
                 */
                $changedVybeAccess = VybeAccessList::getItem(
                    $data['access_id']
                );
            }
        }

        /**
         * Preparing changed vybe access variable
         */
        $changedVybeShowcase = null;

        /**
         * Checking vybe showcase
         */
        if (isset($data['showcase_id'])) {
            if ($vybe->getShowcase()->id != $data['showcase_id']) {

                /**
                 * Getting changed vybe showcase
                 */
                $changedVybeShowcase = VybeShowcaseList::getItem(
                    $data['showcase_id']
                );
            }
        }

        /**
         * Preparing changed vybe order accept variable
         */
        $changedOrderAccept = null;

        /**
         * Checking vybe order accept
         */
        if (isset($data['order_accept_id'])) {
            if (!$vybe->getOrderAccept() && $data['order_accept_id'] ||
                $vybe->getOrderAccept()->id != $data['order_accept_id']
            ) {

                /**
                 * Getting changed order accept
                 */
                $changedOrderAccept = VybeOrderAcceptList::getItem(
                    $data['order_accept_id']
                );
            }
        }

        /**
         * Preparing changed vybe type variable
         */
        $changedVybeType = null;

        /**
         * Checking vybe type data was changed
         */
        if ($changedVybePeriod || $changedUserCount) {

            /**
             * Getting a probable vybe type
             */
            $probableVybeType = $this->vybeService->getVybeTypeByParameters(
                $changedVybePeriod ?: $vybe->getPeriod(),
                $changedUserCount ?: $vybe->user_count
            );

            if ($probableVybeType->id != $vybe->getType()->id) {

                /**
                 * Getting changed vybe type
                 */
                $changedVybeType = $probableVybeType;
            }
        }

        /**
         * Checking vybe images ids changes
         */
        if (compareTwoArrays(
            $vybe->images_ids,
            $changedImagesIds
        )) {

            /**
             * Getting changed vybe images ids
             */
            $changedImagesIds = null;
        }

        /**
         * Checking vybe videos ids changes
         */
        if (compareTwoArrays(
            $vybe->videos_ids,
            $changedVideosIds
        )) {

            /**
             * Getting changed vybe videos ids
             */
            $changedVideosIds = null;
        }

        /**
         * Checking if it has changes
         */
        if ($changedTitle ||
            $changedCategory ||
            $changedSubcategory ||
            $changedActivity ||
            $changedDevicesIds ||
            $changedVybePeriod ||
            $changedUserCount ||
            $changedVybeType ||
            $changedOrderAdvance ||
            $changedImagesIds ||
            $changedVideosIds ||
            $changedVybeAccess ||
            $changedVybeShowcase ||
            $changedOrderAccept
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Vybe $vybe
     * @param array $data
     * @param array|null $changedImagesIds
     * @param array|null $changedVideosIds
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function createVybeChangeRequest(
        Vybe $vybe,
        array $data,
        ?array $changedImagesIds,
        ?array $changedVideosIds
    ) : ?VybeChangeRequest
    {
        /**
         * Preparing changed vybe title variable
         */
        $changedTitle = null;

        /**
         * Preparing previous vybe title variable
         */
        $previousTitle = null;

        /**
         * Check vybe title changes
         */
        if (isset($data['title'])) {
            if (strcmp($vybe->title, $data['title']) !== 0) {

                /**
                 * Getting changed vybe title
                 */
                $changedTitle = $data['title'];

                /**
                 * Getting previous vybe title
                 */
                $previousTitle = $vybe->title;
            }
        }

        /**
         * Preparing changed vybe category variable
         */
        $changedCategory = null;

        /**
         * Preparing previous vybe category variable
         */
        $previousCategory = null;

        /**
         * Checking vybe category changes
         */
        if (isset($data['category_id'])) {
            if ($vybe->category) {
                if ($vybe->category->id != $data['category_id']) {

                    /**
                     * Getting changed vybe category
                     */
                    $changedCategory = $this->categoryRepository->findById(
                        $data['category_id']
                    );

                    /**
                     * Getting changed vybe category
                     */
                    $previousCategory = $vybe->category;
                }
            }
        }

        /**
         * Preparing changed vybe category suggestion variable
         */
        $categorySuggestion = null;

        /**
         * Checking vybe category suggestion changes
         */
        if (isset($data['category_suggestion'])) {

            /**
             * Getting changed vybe category suggestion
             */
            $categorySuggestion = $data['category_suggestion'];

            /**
             * Getting changed vybe category
             */
            $previousCategory = $vybe->category;
        }

        /**
         * Preparing changed vybe subcategory variable
         */
        $changedSubcategory = null;

        /**
         * Preparing previous vybe subcategory variable
         */
        $previousSubcategory = null;

        /**
         * Checking vybe subcategory changes
         */
        if (isset($data['subcategory_id'])) {
            if ($vybe->subcategory) {
                if ($vybe->subcategory->id != $data['subcategory_id']) {

                    /**
                     * Getting changed vybe subcategory
                     */
                    $changedSubcategory = $this->categoryRepository->findById(
                        $data['subcategory_id']
                    );

                    /**
                     * Getting previous vybe subcategory
                     */
                    $previousSubcategory = $vybe->subcategory;
                }
            }
        }

        /**
         * Preparing changed vybe subcategory suggestion variable
         */
        $subcategorySuggestion = null;

        /**
         * Checking vybe subcategory suggestion changes
         */
        if (isset($data['subcategory_suggestion'])) {

            /**
             * Getting changed vybe subcategory suggestion
             */
            $subcategorySuggestion = $data['subcategory_suggestion'];

            /**
             * Getting previous vybe subcategory
             */
            $previousSubcategory = $vybe->subcategory;
        }

        /**
         * Preparing changed vybe activity variable
         */
        $changedActivity = null;

        /**
         * Preparing previous vybe activity variable
         */
        $previousActivity = null;

        /**
         * Checking vybe activity changes
         */
        if (isset($data['activity_id'])) {
            if ($vybe->activity) {
                if ($vybe->activity->id != $data['activity_id']) {

                    /**
                     * Getting changed vybe activity
                     */
                    $changedActivity = $this->activityRepository->findById(
                        $data['activity_id']
                    );

                    /**
                     * Getting previous vybe activity
                     */
                    $previousActivity = $vybe->activity;
                }
            }
        }

        /**
         * Preparing changed vybe activity suggestion variable
         */
        $activitySuggestion = null;

        /**
         * Checking vybe activity suggestion changes
         */
        if (isset($data['activity_suggestion'])) {

            /**
             * Getting changed vybe activity suggestion
             */
            $activitySuggestion = $data['activity_suggestion'];

            /**
             * Getting previous vybe activity
             */
            $previousActivity = $vybe->activity;
        }

        /**
         * Preparing changed vybe devices ids variable
         */
        $changedDevicesIds = null;

        /**
         * Preparing previous vybe devices ids variable
         */
        $previousDevicesIds = null;

        /**
         * Checking vybe devices changes
         */
        if (!compareTwoArrays(
            $vybe->devices ? $vybe->devices->pluck('id')->toArray() : [],
            $data['devices_ids']
        )) {

            /**
             * Getting changed vybe devices ids
             */
            $changedDevicesIds = $data['devices_ids'];

            /**
             * Getting changed vybe devices ids
             */
            $previousDevicesIds = $vybe->devices
                ->pluck('id')
                ->values()
                ->toArray();
        }

        /**
         * Preparing changed vybe device suggestion variable
         */
        $deviceSuggestion = null;

        /**
         * Checking vybe device suggestion changes
         */
        if (isset($data['device_suggestion'])) {

            /**
             * Getting changed vybe device suggestion
             */
            $deviceSuggestion = $data['device_suggestion'];

            /**
             * Getting changed vybe devices ids
             */
            $previousDevicesIds = $vybe->devices
                ->pluck('id')
                ->values()
                ->toArray();

            /**
             * Checking changes devices ids existence
             */
            if (!$changedDevicesIds) {
                $changedDevicesIds = $previousDevicesIds;
            }
        }

        /**
         * Preparing changed vybe period variable
         */
        $changedVybePeriod = null;

        /**
         * Preparing previous vybe period variable
         */
        $previousVybePeriod = null;

        /**
         * Checking vybe period
         */
        if (isset($data['period_id'])) {
            if ($vybe->getPeriod()->id != $data['period_id']) {

                /**
                 * Getting changed vybe period
                 */
                $changedVybePeriod = VybePeriodList::getItem(
                    $data['period_id']
                );

                /**
                 * Getting a previous vybe period
                 */
                $previousVybePeriod = $vybe->getPeriod();
            }
        }

        /**
         * Preparing changed vybe user count variable
         */
        $changedUserCount = null;

        /**
         * Preparing previous vybe user count variable
         */
        $previousUserCount = null;

        /**
         * Checking vybe user count
         */
        if (isset($data['user_count'])) {
            if ($vybe->user_count != $data['user_count']) {

                /**
                 * Getting changed vybe user count
                 */
                $changedUserCount = $data['user_count'];

                /**
                 * Getting previous vybe user count
                 */
                $previousUserCount = $vybe->user_count;
            }
        }

        /**
         * Preparing changed vybe order advance variable
         */
        $changedOrderAdvance = null;

        /**
         * Preparing previous vybe order advance variable
         */
        $previousOrderAdvance = null;

        /**
         * Checking vybe order advance
         */
        if (isset($data['order_advance'])) {
            if ($vybe->order_advance != $data['order_advance']) {

                /**
                 * Getting changed vybe order advance
                 */
                $changedOrderAdvance = $data['order_advance'];

                /**
                 * Getting previous vybe order advance
                 */
                $previousOrderAdvance = $vybe->order_advance;
            }
        }

        /**
         * Preparing changed vybe access variable
         */
        $changedVybeAccess = null;

        /**
         * Preparing previous vybe access variable
         */
        $previousVybeAccess = null;

        /**
         * Checking vybe access
         */
        if (isset($data['access_id'])) {
            if ($vybe->getAccess()->id != $data['access_id']) {

                /**
                 * Getting changed vybe access
                 */
                $changedVybeAccess = VybeAccessList::getItem(
                    $data['access_id']
                );

                /**
                 * Getting previous vybe access
                 */
                $previousVybeAccess = $vybe->getAccess();
            }
        }

        /**
         * Preparing changed vybe access variable
         */
        $changedVybeShowcase = null;

        /**
         * Preparing previous vybe access variable
         */
        $previousVybeShowcase = null;

        /**
         * Checking vybe showcase
         */
        if (isset($data['showcase_id'])) {
            if ($vybe->getShowcase()->id != $data['showcase_id']) {

                /**
                 * Getting changed vybe showcase
                 */
                $changedVybeShowcase = VybeShowcaseList::getItem(
                    $data['showcase_id']
                );

                /**
                 * Getting previous vybe showcase
                 */
                $previousVybeShowcase = $vybe->getShowcase();
            }
        }

        /**
         * Preparing changed vybe order accept variable
         */
        $changedOrderAccept = null;

        /**
         * Preparing changed vybe order accept variable
         */
        $previousOrderAccept = null;

        /**
         * Checking vybe order accept
         */
        if (isset($data['order_accept_id'])) {
            if (!$vybe->getOrderAccept() && $data['order_accept_id'] ||
                $vybe->getOrderAccept()->id != $data['order_accept_id']
            ) {

                /**
                 * Getting changed order accept
                 */
                $changedOrderAccept = VybeOrderAcceptList::getItem(
                    $data['order_accept_id']
                );

                /**
                 * Getting previous order accept
                 */
                $previousOrderAccept = $vybe->getOrderAccept();
            }
        }

        /**
         * Preparing changed vybe type variable
         */
        $changedVybeType = null;

        /**
         * Preparing previous vybe type variable
         */
        $previousVybeType = null;

        /**
         * Checking vybe type data was changed
         */
        if ($changedVybePeriod || $changedUserCount) {

            /**
             * Getting a probable vybe type
             */
            $probableVybeType = $this->vybeService->getVybeTypeByParameters(
                $changedVybePeriod ?: $vybe->getPeriod(),
                $changedUserCount ?: $vybe->user_count
            );

            if ($probableVybeType->id != $vybe->getType()->id) {

                /**
                 * Getting changed vybe type
                 */
                $changedVybeType = $probableVybeType;

                /**
                 * Getting a previous vybe type
                 */
                $previousVybeType = $vybe->getType();
            }
        }

        /**
         * Preparing previous vybe images ids variable
         */
        $previousImagesIds = $vybe->images_ids;

        /**
         * Checking vybe images ids changes
         */
        if (compareTwoArrays(
            $vybe->images_ids,
            $changedImagesIds
        )) {

            /**
             * Getting changed vybe images ids
             */
            $changedImagesIds = null;

            /**
             * Getting previous vybe images ids
             */
            $previousImagesIds = null;
        }

        /**
         * Preparing previous vybe videos ids variable
         */
        $previousVideosIds = $vybe->videos_ids;

        /**
         * Checking vybe videos ids changes
         */
        if (compareTwoArrays(
            $vybe->videos_ids,
            $changedVideosIds
        )) {

            /**
             * Getting changed vybe videos ids
             */
            $changedVideosIds = null;

            /**
             * Getting previous vybe videos ids
             */
            $previousVideosIds = null;
        }

        /**
         * Creating vybe change request
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->store(
            $vybe,
            $changedTitle,
            $previousTitle,
            $changedCategory,
            $previousCategory,
            $categorySuggestion,
            $changedSubcategory,
            $previousSubcategory,
            $subcategorySuggestion,
            $changedActivity,
            $previousActivity,
            $activitySuggestion,
            $changedDevicesIds,
            $previousDevicesIds,
            $deviceSuggestion,
            $changedVybePeriod,
            $previousVybePeriod,
            $changedUserCount,
            $previousUserCount,
            $changedVybeType,
            $previousVybeType,
            $changedOrderAdvance,
            $previousOrderAdvance,
            $changedImagesIds,
            $previousImagesIds,
            $changedVideosIds,
            $previousVideosIds,
            $changedVybeAccess,
            $previousVybeAccess,
            $changedVybeShowcase,
            $previousVybeShowcase,
            $changedOrderAccept,
            $previousOrderAccept,
            null,
            null
        );

        /**
         * Checking vybe change request existence
         */
        if ($vybeChangeRequest) {

            /**
             * Updating vybe change request
             */
            $this->vybeChangeRequestRepository->updateLanguage(
                $vybeChangeRequest,
                $vybe->user->getLanguage()
            );
        }

        /**
         * Releasing vybe change request counter-caches
         */
        $this->adminNavbarService->releaseVybeChangeRequestCache();

        /**
         * Releasing suggestion cache
         */
        $this->adminNavbarService->releaseAllSuggestionCache();

        return $vybeChangeRequest;
    }

    /**
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function haveAppearanceCasesChanges(
        Vybe $vybe,
        array $appearanceCases
    ) : bool
    {
        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /**
             * Getting vybe appearance case
             */
            $currentAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybe,
                VybeAppearanceList::getVoiceChat()
            );

            /**
             * Checking a current appearance case exists
             */
            if ($currentAppearanceCase) {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $changedPrice = null;

                /**
                 * Checking vybe appearance case price existence
                 */
                if (isset($voiceChat['price'])) {
                    if ($currentAppearanceCase->price != $voiceChat['price']) {

                        /**
                         * Getting changed vybe appearance case price
                         */
                        $changedPrice = $voiceChat['price'];
                    }
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $changedDescription = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($voiceChat['description'])) {
                    if ($currentAppearanceCase->description != $voiceChat['description']) {

                        /**
                         * Getting changed vybe appearance case description
                         */
                        $changedDescription = $voiceChat['description'];
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $changedUnit = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($voiceChat['unit_id'])) {
                    if ($currentAppearanceCase->unit_id != $voiceChat['unit_id']) {

                        /**
                         * Getting changed vybe appearance case unit
                         */
                        $changedUnit = $this->unitRepository->findById(
                            $voiceChat['unit_id']
                        );
                    }
                }

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $changedUnit = $voiceChat['unit_suggestion'];
                }

                /**
                 * Preparing changed vybe appearance case platforms ids variable
                 */
                $changedPlatformsIds = null;

                /**
                 * Checking vybe appearance case platforms ids existence
                 */
                if (isset($voiceChat['platforms_ids'])) {

                    /**
                     * Checking vybe devices changes
                     */
                    if (!compareTwoArrays(
                        $currentAppearanceCase->platforms ?
                            $currentAppearanceCase->platforms->pluck('id')->toArray() :
                            null, $voiceChat['platforms_ids']
                    )) {

                        /**
                         * Getting changed vybe appearance case platforms ids
                         */
                        $changedPlatformsIds = $voiceChat['platforms_ids'];
                    }
                }

                /**
                 * Checking if it has changes
                 */
                if ($changedPrice ||
                    $changedDescription ||
                    $changedUnit ||
                    $changedPlatformsIds
                ) {
                    return true;
                }
            }
        }

        /**
         * Checking video chat existence
         */
        if (isset($appearanceCases['video_chat'])) {

            /**
             * Getting video chat
             */
            $videoChat = $appearanceCases['video_chat'];

            /**
             * Getting vybe appearance case
             */
            $currentAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybe,
                VybeAppearanceList::getVideoChat()
            );

            /**
             * Checking a current appearance case exists
             */
            if ($currentAppearanceCase) {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $changedPrice = null;

                /**
                 * Checking vybe appearance case price existence
                 */
                if (isset($videoChat['price'])) {
                    if ($currentAppearanceCase->price != $videoChat['price']) {

                        /**
                         * Getting changed vybe appearance case price
                         */
                        $changedPrice = $videoChat['price'];
                    }
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $changedDescription = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($videoChat['description'])) {
                    if ($currentAppearanceCase->description != $videoChat['description']) {

                        /**
                         * Getting changed vybe appearance case description
                         */
                        $changedDescription = $videoChat['description'];
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $changedUnit = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($videoChat['unit_id'])) {
                    if ($currentAppearanceCase->unit_id != $videoChat['unit_id']) {

                        /**
                         * Getting changed vybe appearance case unit
                         */
                        $changedUnit = $this->unitRepository->findById(
                            $videoChat['unit_id']
                        );
                    }
                }

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $changedUnit = $videoChat['unit_suggestion'];
                }

                /**
                 * Preparing changed vybe appearance case platforms ids variable
                 */
                $changedPlatformsIds = null;

                /**
                 * Checking vybe appearance case platforms ids existence
                 */
                if (isset($videoChat['platforms_ids'])) {

                    /**
                     * Checking vybe devices changes
                     */
                    if (!compareTwoArrays(
                        $currentAppearanceCase->platforms ?
                            $currentAppearanceCase->platforms->pluck('id')->toArray() :
                            null, $videoChat['platforms_ids']
                    )) {

                        /**
                         * Getting changed vybe appearance case platforms ids
                         */
                        $changedPlatformsIds = $videoChat['platforms_ids'];
                    }
                }

                /**
                 * Checking if it has changes
                 */
                if ($changedPrice ||
                    $changedDescription ||
                    $changedUnit ||
                    $changedPlatformsIds
                ) {
                    return true;
                }
            }
        }

        /**
         * Checking real life existence
         */
        if (isset($appearanceCases['real_life'])) {

            /**
             * Getting real life
             */
            $realLife = $appearanceCases['real_life'];

            /**
             * Getting vybe appearance case
             */
            $currentAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybe,
                VybeAppearanceList::getRealLife()
            );

            /**
             * Checking a current appearance case exists
             */
            if ($currentAppearanceCase) {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $changedPrice = null;

                /**
                 * Checking vybe appearance case price existence
                 */
                if (isset($realLife['price'])) {
                    if ($currentAppearanceCase->price != $realLife['price']) {

                        /**
                         * Getting changed vybe appearance case price
                         */
                        $changedPrice = $realLife['price'];
                    }
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $changedDescription = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($realLife['description'])) {
                    if ($currentAppearanceCase->description != $realLife['description']) {

                        /**
                         * Getting changed vybe appearance case description
                         */
                        $changedDescription = $realLife['description'];
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $changedUnit = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($realLife['unit_id'])) {
                    if ($currentAppearanceCase->unit_id != $realLife['unit_id']) {

                        /**
                         * Getting changed vybe appearance case unit
                         */
                        $changedUnit = $this->unitRepository->findById(
                            $realLife['unit_id']
                        );
                    }
                }

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $changedUnit = $realLife['unit_suggestion'];
                }

                /**
                 * Preparing changed vybe appearance case city place variable
                 */
                $changedCityPlace = null;

                /**
                 * Checking vybe appearance case same location changes
                 */
                if (isset($realLife['same_location'])) {

                    /**
                     * Checking vybe appearance case city place id existence
                     */
                    if (isset($realLife['city_place_id'])) {
                        if ($currentAppearanceCase->cityPlace) {
                            if ($currentAppearanceCase->city_place_id != $realLife['city_place_id']) {

                                /**
                                 * Getting city place
                                 */
                                $changedCityPlace = $this->cityPlaceRepository->findByPlaceId(
                                    $realLife['city_place_id']
                                );
                            }
                        }
                    }
                }

                /**
                 * Checking if it has changes
                 */
                if ($changedPrice ||
                    $changedDescription ||
                    $changedUnit ||
                    $changedCityPlace
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $appearanceCases
     *
     * @return Collection
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function createAppearanceCases(
        VybeChangeRequest $vybeChangeRequest,
        array $appearanceCases
    ) : Collection
    {
        /**
         * Preparing vybe change request appearance cases collection
         */
        $vybeChangeRequestAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /**
             * Getting vybe appearance case
             */
            $currentAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybeChangeRequest->vybe,
                VybeAppearanceList::getVoiceChat()
            );

            /**
             * Checking a current appearance case exists
             */
            if ($currentAppearanceCase) {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $changedPrice = null;

                /**
                 * Preparing previous vybe appearance case price variable
                 */
                $previousPrice = null;

                /**
                 * Checking vybe appearance case price existence
                 */
                if (isset($voiceChat['price'])) {
                    if ($currentAppearanceCase->price != $voiceChat['price']) {

                        /**
                         * Getting changed vybe appearance case price
                         */
                        $changedPrice = $voiceChat['price'];

                        /**
                         * Getting previous vybe appearance case price
                         */
                        $previousPrice = $currentAppearanceCase->price;
                    }
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $changedDescription = null;

                /**
                 * Preparing previous vybe appearance case description variable
                 */
                $previousDescription = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($voiceChat['description'])) {
                    if ($currentAppearanceCase->description != $voiceChat['description']) {

                        /**
                         * Getting changed vybe appearance case description
                         */
                        $changedDescription = $voiceChat['description'];

                        /**
                         * Getting previous vybe appearance case description
                         */
                        $previousDescription = $currentAppearanceCase->description;
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $changedUnit = null;

                /**
                 * Preparing previous vybe appearance case unit variable
                 */
                $previousUnit = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($voiceChat['unit_id'])) {
                    if ($currentAppearanceCase->unit_id != $voiceChat['unit_id']) {

                        /**
                         * Getting changed vybe appearance case unit
                         */
                        $changedUnit = $this->unitRepository->findById(
                            $voiceChat['unit_id']
                        );

                        /**
                         * Getting previous vybe appearance case unit
                         */
                        $previousUnit = $currentAppearanceCase->unit;
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit suggestion variable
                 */
                $unitSuggestion = null;

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $unitSuggestion = $voiceChat['unit_suggestion'];

                    /**
                     * Getting previous vybe appearance case unit
                     */
                    $previousUnit = $currentAppearanceCase->unit;
                }

                /**
                 * Preparing changed vybe appearance case platforms ids variable
                 */
                $changedPlatformsIds = null;

                /**
                 * Preparing previous vybe appearance case platforms ids variable
                 */
                $previousPlatformsIds = null;

                /**
                 * Checking vybe appearance case platforms ids existence
                 */
                if (isset($voiceChat['platforms_ids'])) {

                    /**
                     * Checking vybe devices changes
                     */
                    if (!compareTwoArrays(
                        $currentAppearanceCase->platforms ?
                            $currentAppearanceCase->platforms->pluck('id')->toArray() :
                            null, $voiceChat['platforms_ids']
                    )) {

                        /**
                         * Getting changed vybe appearance case platforms ids
                         */
                        $changedPlatformsIds = $voiceChat['platforms_ids'];

                        /**
                         * Getting previous vybe appearance case platforms ids
                         */
                        $previousPlatformsIds = $currentAppearanceCase->platforms
                            ->pluck('id')
                            ->values()
                            ->toArray();
                    }
                }

                /**
                 * Preparing changed vybe appearance case enabled variable
                 */
                $changedEnabled = $currentAppearanceCase->enabled;

                /**
                 * Checking vybe enabled changes
                 */
                if (isset($voiceChat['enabled'])) {
                    if ($currentAppearanceCase->enabled !== $voiceChat['enabled']) {

                        /**
                         * Getting vybe appearance case enabled
                         */
                        $changedEnabled = $voiceChat['enabled'];
                    }
                }

                /**
                 * Creating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->store(
                    $vybeChangeRequest,
                    VybeAppearanceList::getVoiceChat(),
                    $changedPrice,
                    $previousPrice,
                    $changedUnit,
                    $previousUnit,
                    $unitSuggestion,
                    $changedDescription,
                    $previousDescription,
                    $changedPlatformsIds,
                    $previousPlatformsIds,
                    null,
                    null,
                    null,
                    null,
                    $changedEnabled,
                    $currentAppearanceCase->enabled
                );
            } else {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $price = null;

                if (isset($voiceChat['price'])) {

                    /**
                     * Getting vybe appearance case price
                     */
                    $price = $voiceChat['price'];
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $unit = null;

                if (isset($voiceChat['unit_id'])) {

                    /**
                     * Getting vybe appearance case unit
                     */
                    $unit = $this->unitRepository->findById(
                        $voiceChat['unit_id']
                    );
                }

                /**
                 * Preparing changed vybe appearance case unit suggestion variable
                 */
                $unitSuggestion = null;

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $unitSuggestion = $voiceChat['unit_suggestion'];
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $description = null;

                /**
                 * Checking vybe description changes
                 */
                if (isset($voiceChat['description'])) {

                    /**
                     * Getting changed vybe description
                     */
                    $description = $voiceChat['description'];
                }

                /**
                 * Preparing changed vybe appearance case platforms ids variable
                 */
                $platformsIds = null;

                /**
                 * Checking vybe platforms ids change
                 */
                if (isset($voiceChat['platforms_ids'])) {

                    /**
                     * Getting changed vybe platforms ids
                     */
                    $platformsIds = $voiceChat['platforms_ids'];
                }

                /**
                 * Preparing vybe appearance case enabled variable
                 */
                $enabled = null;

                /**
                 * Checking vybe enabled changes
                 */
                if (isset($voiceChat['enabled'])) {

                    /**
                     * Getting vybe appearance case enabled
                     */
                    $enabled = $voiceChat['enabled'];
                }

                /**
                 * Creating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->store(
                    $vybeChangeRequest,
                    VybeAppearanceList::getVoiceChat(),
                    $price,
                    null,
                    $unit,
                    null,
                    $unitSuggestion,
                    $description,
                    null,
                    $platformsIds,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $enabled,
                    null
                );
            }

            $vybeChangeRequestAppearanceCases->push(
                $vybeChangeRequestAppearanceCase
            );
        }

        /**
         * Checking video chat existence
         */
        if (isset($appearanceCases['video_chat'])) {

            /**
             * Getting video chat
             */
            $videoChat = $appearanceCases['video_chat'];

            /**
             * Getting vybe appearance case
             */
            $currentAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybeChangeRequest->vybe,
                VybeAppearanceList::getVideoChat()
            );

            /**
             * Checking a current appearance case exists
             */
            if ($currentAppearanceCase) {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $changedPrice = null;

                /**
                 * Preparing previous vybe appearance case price variable
                 */
                $previousPrice = null;

                /**
                 * Checking vybe appearance case price existence
                 */
                if (isset($videoChat['price'])) {
                    if ($currentAppearanceCase->price != $videoChat['price']) {

                        /**
                         * Getting changed vybe appearance case price
                         */
                        $changedPrice = $videoChat['price'];

                        /**
                         * Getting previous vybe appearance case price
                         */
                        $previousPrice = $currentAppearanceCase->price;
                    }
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $changedDescription = null;

                /**
                 * Preparing previous vybe appearance case description variable
                 */
                $previousDescription = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($videoChat['description'])) {
                    if ($currentAppearanceCase->description != $videoChat['description']) {

                        /**
                         * Getting changed vybe appearance case description
                         */
                        $changedDescription = $videoChat['description'];

                        /**
                         * Getting previous vybe appearance case description
                         */
                        $previousDescription = $currentAppearanceCase->description;
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $changedUnit = null;

                /**
                 * Preparing previous vybe appearance case unit variable
                 */
                $previousUnit = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($videoChat['unit_id'])) {
                    if ($currentAppearanceCase->unit_id != $videoChat['unit_id']) {

                        /**
                         * Getting changed vybe appearance case unit
                         */
                        $changedUnit = $this->unitRepository->findById(
                            $videoChat['unit_id']
                        );

                        /**
                         * Getting previous vybe appearance case unit
                         */
                        $previousUnit = $currentAppearanceCase->unit;
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit suggestion variable
                 */
                $unitSuggestion = null;

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $unitSuggestion = $videoChat['unit_suggestion'];

                    /**
                     * Getting previous vybe appearance case unit
                     */
                    $previousUnit = $currentAppearanceCase->unit;
                }

                /**
                 * Preparing changed vybe appearance case platforms ids variable
                 */
                $changedPlatformsIds = null;

                /**
                 * Preparing previous vybe appearance case platforms ids variable
                 */
                $previousPlatformsIds = null;

                /**
                 * Checking vybe appearance case platforms ids existence
                 */
                if (isset($videoChat['platforms_ids'])) {

                    /**
                     * Checking vybe devices changes
                     */
                    if (!compareTwoArrays(
                        $currentAppearanceCase->platforms ?
                            $currentAppearanceCase->platforms->pluck('id')->toArray() :
                            null, $videoChat['platforms_ids']
                    )) {

                        /**
                         * Getting changed vybe appearance case platforms ids
                         */
                        $changedPlatformsIds = $videoChat['platforms_ids'];

                        /**
                         * Getting previous vybe appearance case platforms ids
                         */
                        $previousPlatformsIds = $currentAppearanceCase->platforms
                            ->pluck('id')
                            ->values()
                            ->toArray();
                    }
                }

                /**
                 * Preparing changed vybe appearance case enabled variable
                 */
                $changedEnabled = $currentAppearanceCase->enabled;

                /**
                 * Checking vybe enabled changes
                 */
                if (isset($videoChat['enabled'])) {
                    if ($currentAppearanceCase->enabled !== $videoChat['enabled']) {

                        /**
                         * Getting vybe appearance case enabled
                         */
                        $changedEnabled = $videoChat['enabled'];
                    }
                }

                /**
                 * Creating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->store(
                    $vybeChangeRequest,
                    VybeAppearanceList::getVideoChat(),
                    $changedPrice,
                    $previousPrice,
                    $changedUnit,
                    $previousUnit,
                    $unitSuggestion,
                    $changedDescription,
                    $previousDescription,
                    $changedPlatformsIds,
                    $previousPlatformsIds,
                    null,
                    null,
                    null,
                    null,
                    $changedEnabled,
                    $currentAppearanceCase->enabled
                );
            } else {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $price = null;

                if (isset($videoChat['price'])) {

                    /**
                     * Getting vybe appearance case price
                     */
                    $price = $videoChat['price'];
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $unit = null;

                if (isset($videoChat['unit_id'])) {

                    /**
                     * Getting vybe appearance case unit
                     */
                    $unit = $this->unitRepository->findById(
                        $videoChat['unit_id']
                    );
                }

                /**
                 * Preparing changed vybe appearance case unit suggestion variable
                 */
                $unitSuggestion = null;

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $unitSuggestion = $videoChat['unit_suggestion'];
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $description = null;

                /**
                 * Checking vybe description changes
                 */
                if (isset($videoChat['description'])) {

                    /**
                     * Getting changed vybe description
                     */
                    $description = $videoChat['description'];
                }

                /**
                 * Preparing changed vybe appearance case platforms ids variable
                 */
                $platformsIds = null;

                /**
                 * Checking vybe platforms ids change
                 */
                if (isset($videoChat['platforms_ids'])) {

                    /**
                     * Getting changed vybe platforms ids
                     */
                    $platformsIds = $videoChat['platforms_ids'];
                }

                /**
                 * Preparing vybe appearance case enabled variable
                 */
                $enabled = null;

                /**
                 * Checking vybe enabled changes
                 */
                if (isset($videoChat['enabled'])) {

                    /**
                     * Getting vybe appearance case enabled
                     */
                    $enabled = $videoChat['enabled'];
                }

                /**
                 * Creating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->store(
                    $vybeChangeRequest,
                    VybeAppearanceList::getVideoChat(),
                    $price,
                    null,
                    $unit,
                    null,
                    $unitSuggestion,
                    $description,
                    null,
                    $platformsIds,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $enabled,
                    null
                );
            }

            $vybeChangeRequestAppearanceCases->push(
                $vybeChangeRequestAppearanceCase
            );
        }

        /**
         * Checking real life existence
         */
        if (isset($appearanceCases['real_life'])) {

            /**
             * Getting real life
             */
            $realLife = $appearanceCases['real_life'];

            /**
             * Getting vybe appearance case
             */
            $currentAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybeChangeRequest->vybe,
                VybeAppearanceList::getRealLife()
            );

            /**
             * Checking a current appearance case exists
             */
            if ($currentAppearanceCase) {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $changedPrice = null;

                /**
                 * Preparing previous vybe appearance case price variable
                 */
                $previousPrice = null;

                /**
                 * Checking vybe appearance case price existence
                 */
                if (isset($realLife['price'])) {
                    if ($currentAppearanceCase->price != $realLife['price']) {

                        /**
                         * Getting changed vybe appearance case price
                         */
                        $changedPrice = $realLife['price'];

                        /**
                         * Getting previous vybe appearance case price
                         */
                        $previousPrice = $currentAppearanceCase->price;
                    }
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $changedDescription = null;

                /**
                 * Preparing previous vybe appearance case description variable
                 */
                $previousDescription = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($realLife['description'])) {
                    if ($currentAppearanceCase->description != $realLife['description']) {

                        /**
                         * Getting changed vybe appearance case description
                         */
                        $changedDescription = $realLife['description'];

                        /**
                         * Getting previous vybe appearance case description
                         */
                        $previousDescription = $currentAppearanceCase->description;
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $changedUnit = null;

                /**
                 * Preparing previous vybe appearance case unit variable
                 */
                $previousUnit = null;

                /**
                 * Checking vybe appearance case description existence
                 */
                if (isset($realLife['unit_id'])) {
                    if ($currentAppearanceCase->unit_id != $realLife['unit_id']) {

                        /**
                         * Getting changed vybe appearance case unit
                         */
                        $changedUnit = $this->unitRepository->findById(
                            $realLife['unit_id']
                        );

                        /**
                         * Getting previous vybe appearance case unit
                         */
                        $previousUnit = $currentAppearanceCase->unit;
                    }
                }

                /**
                 * Preparing changed vybe appearance case unit suggestion variable
                 */
                $unitSuggestion = null;

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $unitSuggestion = $realLife['unit_suggestion'];

                    /**
                     * Getting previous vybe appearance case unit
                     */
                    $previousUnit = $currentAppearanceCase->unit;
                }

                /**
                 * Preparing changed vybe appearance case city place variable
                 */
                $changedCityPlace = null;

                /**
                 * Preparing changed vybe appearance case previous city place variable
                 */
                $previousCityPlace = null;

                /**
                 * Preparing changed vybe appearance case same location variable
                 */
                $sameLocation = null;

                /**
                 * Preparing previous vybe appearance case same location variable
                 */
                $previousSameLocation = null;

                /**
                 * Checking city place changes
                 */
                if ($currentAppearanceCase->city_place_id != $realLife['city_place_id']) {

                    /**
                     * Getting city place
                     */
                    $changedCityPlace = $this->cityPlaceRepository->findByPlaceId(
                        $realLife['city_place_id']
                    );

                    /**
                     * Checking changed city place existence
                     */
                    if (!$changedCityPlace) {

                        /**
                         * Creating city place
                         */
                        $changedCityPlace = $this->cityPlaceService->getOrCreate(
                            $realLife['city_place_id']
                        );
                    }

                    /**
                     * Checking city place existence
                     */
                    if (isset($currentAppearanceCase->cityPlace)) {
                        $previousCityPlace = $currentAppearanceCase->cityPlace;
                    }
                }

                /**
                 * Checking same location changes
                 */
                if ($realLife['same_location'] != $currentAppearanceCase->same_location) {
                    $sameLocation = $realLife['same_location'];

                    $previousSameLocation = $currentAppearanceCase->same_location;
                }

                /**
                 * Preparing changed vybe appearance case enabled variable
                 */
                $changedEnabled = $currentAppearanceCase->enabled;

                /**
                 * Checking vybe enabled changes
                 */
                if (isset($realLife['enabled'])) {
                    if ($currentAppearanceCase->enabled !== $realLife['enabled']) {

                        /**
                         * Getting vybe appearance case enabled
                         */
                        $changedEnabled = $realLife['enabled'];
                    }
                }

                /**
                 * Creating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->store(
                    $vybeChangeRequest,
                    VybeAppearanceList::getRealLife(),
                    $changedPrice,
                    $previousPrice,
                    $changedUnit,
                    $previousUnit,
                    $unitSuggestion,
                    $changedDescription,
                    $previousDescription,
                    null,
                    null,
                    $sameLocation,
                    $previousSameLocation,
                    $changedCityPlace,
                    $previousCityPlace,
                    $changedEnabled,
                    $currentAppearanceCase->enabled
                );
            } else {

                /**
                 * Preparing changed vybe appearance case price variable
                 */
                $price = null;

                if (isset($realLife['price'])) {

                    /**
                     * Getting vybe appearance case price
                     */
                    $price = $realLife['price'];
                }

                /**
                 * Preparing changed vybe appearance case unit variable
                 */
                $unit = null;

                if (isset($realLife['unit_id'])) {

                    /**
                     * Getting vybe appearance case unit
                     */
                    $unit = $this->unitRepository->findById(
                        $realLife['unit_id']
                    );
                }

                /**
                 * Preparing changed vybe appearance case unit suggestion variable
                 */
                $unitSuggestion = null;

                /**
                 * Checking vybe unit suggestion changes
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Getting changed vybe unit suggestion
                     */
                    $unitSuggestion = $realLife['unit_suggestion'];
                }

                /**
                 * Preparing changed vybe appearance case description variable
                 */
                $description = null;

                /**
                 * Checking vybe description changes
                 */
                if (isset($realLife['description'])) {

                    /**
                     * Getting changed vybe description
                     */
                    $description = $realLife['description'];
                }

                /**
                 * Preparing vybe appearance case city place variable
                 */
                $cityPlace = null;

                /**
                 * Preparing changed vybe appearance case same location variable
                 */
                $sameLocation = null;

                /**
                 * Checking vybe appearance case same location changes
                 */
                if (isset($realLife['same_location'])) {

                    /**
                     * Checking vybe appearance case city id existence
                     */
                    if (isset($realLife['city_place_id'])) {

                        /**
                         * Getting city place
                         */
                        $cityPlace = $this->cityPlaceRepository->findByPlaceId(
                            $realLife['city_place_id']
                        );

                        /**
                         * Checking changed city place existence
                         */
                        if (!$cityPlace) {

                            /**
                             * Creating city place
                             */
                            $cityPlace = $this->cityPlaceService->getOrCreate(
                                $realLife['city_place_id']
                            );
                        }
                    }

                    /**
                     * Getting changed vybe appearance case same location
                     */
                    $sameLocation = $realLife['same_location'];
                }

                /**
                 * Preparing vybe appearance case enabled variable
                 */
                $enabled = null;

                /**
                 * Checking vybe enabled changes
                 */
                if (isset($realLife['enabled'])) {

                    /**
                     * Getting vybe appearance case enabled
                     */
                    $enabled = $realLife['enabled'];
                }

                /**
                 * Creating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->store(
                    $vybeChangeRequest,
                    VybeAppearanceList::getRealLife(),
                    $price,
                    null,
                    $unit,
                    null,
                    $unitSuggestion,
                    $description,
                    null,
                    null,
                    null,
                    $sameLocation,
                    null,
                    $cityPlace,
                    null,
                    $enabled,
                    null
                );
            }

            $vybeChangeRequestAppearanceCases->push(
                $vybeChangeRequestAppearanceCase
            );
        }

        return $vybeChangeRequestAppearanceCases;
    }

    /**
     * @param Vybe $vybe
     * @param array $schedulesItems
     *
     * @return bool
     */
    public function haveSchedulesChanges(
        Vybe $vybe,
        array $schedulesItems
    ) : bool
    {
        /**
         * Getting vybe schedules
         */
        $vybeSchedules = $vybe->schedules;

        /**
         * Comparing vybe schedules with changed
         */
        if ($vybeSchedules->count() == count($schedulesItems)) {

            /** @var array $scheduleItem */
            foreach ($schedulesItems as $scheduleItem) {

                /**
                 * Checking schedule existence
                 */
                if (!$vybe->schedules()
                    ->where('datetime_from', '=', getCarbonDateTime($scheduleItem['datetime_from']))
                    ->where('datetime_to', '=', getCarbonDateTime($scheduleItem['datetime_to']))
                    ->exists()
                ) {
                    return true;
                }
            }
        } else {
            return true;
        }

        return false;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $schedulesItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createSchedules(
        VybeChangeRequest $vybeChangeRequest,
        array $schedulesItems
    ) : Collection
    {
        /**
         * Getting vybe schedules
         */
        $vybeSchedules = $vybeChangeRequest->vybe
            ->schedules;

        /**
         * Preparing vybe change request schedules collection
         */
        $vybeChangeRequestSchedules = new Collection();

        /**
         * Preparing has change variable
         */
        $hasChanges = false;

        /**
         * Comparing vybe schedules with changed
         */
        if ($vybeSchedules->count() == count($schedulesItems)) {

            /** @var array $scheduleItem */
            foreach ($schedulesItems as $scheduleItem) {

                /**
                 * Checking schedule existence
                 */
                if (!$vybeChangeRequest->vybe
                    ->schedules()
                    ->where('datetime_from', '=', getCarbonDateTime($scheduleItem['datetime_from']))
                    ->where('datetime_to', '=', getCarbonDateTime($scheduleItem['datetime_to']))
                    ->exists()
                ) {
                    $hasChanges = true;
                }
            }
        } else {
            $hasChanges = true;
        }

        /**
         * Checking has changes
         */
        if ($hasChanges) {

            /** @var array $scheduleItem */
            foreach ($schedulesItems as $scheduleItem) {

                /**
                 * Creating vybe change request schedule
                 */
                $vybeChangeRequestSchedule = $this->vybeChangeRequestScheduleRepository->store(
                    $vybeChangeRequest,
                    getCarbonDateTime($scheduleItem['datetime_from']),
                    getCarbonDateTime($scheduleItem['datetime_to'])
                );

                /**
                 * Adding vybe change request schedule to a collection
                 */
                $vybeChangeRequestSchedules->push(
                    $vybeChangeRequestSchedule
                );
            }

            /**
             * Updating vybe change request
             */
            $this->vybeChangeRequestRepository->updateSchedulesStatus(
                $vybeChangeRequest,
                RequestFieldStatusList::getPendingItem()
            );
        }

        return $vybeChangeRequestSchedules;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return VybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateByCsauSuggestions(
        VybeChangeRequest $vybeChangeRequest
    ) : VybeChangeRequest
    {
        /**
         * Getting vybe change request CSAU suggestions
         */
        $csauSuggestions = $this->csauSuggestionRepository->getAllForVybeChangeRequest(
            $vybeChangeRequest
        );

        /**
         * Checking CSAU suggestions exist
         */
        if ($csauSuggestions->count()) {

            /**
             * Checking vybe change request category suggested
             */
            if (!$vybeChangeRequest->category) {

                /**
                 * Checking is CSAU suggestion category accepted
                 */
                if ($csauSuggestions->first()->category) {

                    /**
                     * Update vybe change request category
                     */
                    $this->vybeChangeRequestRepository->updateSuggestedCategory(
                        $vybeChangeRequest,
                        $csauSuggestions->first()->category
                    );
                }
            }

            /**
             * Checking vybe change request subcategory suggested
             */
            if (!$vybeChangeRequest->subcategory) {

                /**
                 * Checking is CSAU suggestion subcategory accepted
                 */
                if ($csauSuggestions->first()->subcategory) {

                    /**
                     * Updating vybe change request subcategory
                     */
                    $this->vybeChangeRequestRepository->updateSuggestedSubcategory(
                        $vybeChangeRequest,
                        $csauSuggestions->first()->subcategory
                    );
                }
            }

            /**
             * Checking vybe change request activity suggested
             */
            if (!$vybeChangeRequest->activity) {

                /**
                 * Checking is CSAU suggestion activity accepted
                 */
                if ($csauSuggestions->first()->activity) {

                    /**
                     * Updating vybe change request activity
                     */
                    $this->vybeChangeRequestRepository->updateSuggestedActivity(
                        $vybeChangeRequest,
                        $csauSuggestions->first()->activity
                    );
                }
            }

            /** @var VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase */
            foreach ($vybeChangeRequest->appearanceCases as $vybeChangeRequestAppearanceCase) {

                /** @var CsauSuggestion $csauSuggestion */
                foreach ($csauSuggestions as $csauSuggestion) {

                    /**
                     * Checking CSAU suggestion conformity
                     */
                    if ($vybeChangeRequestAppearanceCase->csauSuggestion &&
                        $vybeChangeRequestAppearanceCase->csauSuggestion->_id == $csauSuggestion->_id
                    ) {

                        /**
                         * Updating vybe change request appearance case
                         */
                        $this->vybeChangeRequestAppearanceCaseRepository->updateUnit(
                            $vybeChangeRequestAppearanceCase,
                            $csauSuggestion->unit,
                            $csauSuggestion->unit_suggestion
                        );
                    }
                }
            }
        }

        return $vybeChangeRequest;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateByDeviceSuggestion(
        VybeChangeRequest $vybeChangeRequest,
        DeviceSuggestion $deviceSuggestion
    ) : VybeChangeRequest
    {
        /**
         * Checking device suggestion existence
         */
        if ($deviceSuggestion->device) {

            /**
             * Updating device suggestion
             */
            $this->vybeChangeRequestRepository->updateSuggestedDevice(
                $vybeChangeRequest,
                $deviceSuggestion->device
            );
        }

        return $vybeChangeRequest;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $appearanceCases
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateAppearanceCasesStatuses(
        VybeChangeRequest $vybeChangeRequest,
        array $appearanceCases
    ) : bool
    {
        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCasesStatuses['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCasesStatuses['voice_chat'];

            /** @var VybeChangeRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybeChangeRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVoiceChat()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if (!$appearanceCase) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybeChangeRequest.' . __FUNCTION__ . '.voiceChat'),
                    'appearance_cases.voice_chat.absence'
                );
            }

            /**
             * Getting CSAU suggestion
             */
            $csauSuggestion = $appearanceCase->csauSuggestion;

            /**
             * Getting an appearance case price status existence
             */
            if ($appearanceCase->getPriceStatus()) {

                /**
                 * Getting price status
                 */
                $priceStatus = RequestFieldStatusList::getItem(
                    $voiceChat['price_status_id'] ?? null
                );

                /**
                 * Checking price status existence
                 */
                if ($appearanceCase->getPriceStatus()->isPending() &&
                    !$priceStatus
                ) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.priceStatus'),
                        'appearance_cases.voice_chat.price_status_id'
                    );
                }
            }

            /**
             * Getting an appearance case unit status existence
             */
            if ($appearanceCase->getUnitStatus()) {

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $voiceChat['unit_status_id'] ?? null
                );

                /**
                 * Checking unit status existence
                 */
                if ($appearanceCase->getUnitStatus()->isPending() &&
                    !$unitStatus
                ) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.unitStatus'),
                        'appearance_cases.voice_chat.unit_status_id'
                    );
                } else {

                    /**
                     * Checking CSAU suggestion existence
                     */
                    if ($csauSuggestion) {

                        /**
                         * Checking CSAU suggestion unit status
                         */
                        if ($appearanceCase->getUnitStatus() &&
                            $csauSuggestion->getUnitStatus()->isPending() &&
                            $unitStatus->isAccepted()
                        ) {
                            throw new ValidationException(
                                trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.csau.unit.pending'),
                                'appearance_cases.voice_chat.unit_status_id'
                            );
                        }
                    }
                }
            }

            /**
             * Checking appearance case description status existence
             */
            if ($appearanceCase->getDescriptionStatus()) {

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $voiceChat['description_status_id'] ?? null
                );

                /**
                 * Checking description status existence
                 */
                if (!$descriptionStatus) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.descriptionStatus'),
                        'appearance_cases.voice_chat.description_status_id'
                    );
                }
            }

            /**
             * Checking appearance case platforms status existence
             */
            if ($appearanceCase->getPlatformsStatus()) {

                /**
                 * Getting platforms status
                 */
                $platformsStatus = RequestFieldStatusList::getItem(
                    $voiceChat['platforms_status_id'] ?? null
                );

                /**
                 * Checking platforms status existence
                 */
                if (!$platformsStatus) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.platformsStatus'),
                        'appearance_cases.voice_chat.platforms_status_id'
                    );
                }
            }
        }

        /**
         * Checking video chat existence
         */
        if (isset($appearanceCasesStatuses['video_chat'])) {

            /**
             * Getting video chat
             */
            $videoChat = $appearanceCasesStatuses['video_chat'];

            /** @var VybeChangeRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybeChangeRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVideoChat()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if (!$appearanceCase) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybeChangeRequest.' . __FUNCTION__ . '.videoChat'),
                    'appearance_cases.video_chat.absence'
                );
            }

            /**
             * Getting CSAU suggestion
             */
            $csauSuggestion = $appearanceCase->csauSuggestion;

            /**
             * Getting an appearance case price status existence
             */
            if ($appearanceCase->getPriceStatus()) {

                /**
                 * Getting price status
                 */
                $priceStatus = RequestFieldStatusList::getItem(
                    $videoChat['price_status_id'] ?? null
                );

                /**
                 * Checking price status existence
                 */
                if ($appearanceCase->getPriceStatus()->isPending() &&
                    !$priceStatus
                ) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.priceStatus'),
                        'appearance_cases.video_chat.price_status_id'
                    );
                }
            }

            /**
             * Getting an appearance case unit status existence
             */
            if ($appearanceCase->getUnitStatus()) {

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $videoChat['unit_status_id'] ?? null
                );

                /**
                 * Checking unit status existence
                 */
                if ($appearanceCase->getUnitStatus()->isPending() &&
                    !$unitStatus
                ) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.unitStatus'),
                        'appearance_cases.video_chat.unit_status_id'
                    );
                } else {

                    /**
                     * Checking CSAU suggestion existence
                     */
                    if ($csauSuggestion) {

                        /**
                         * Checking CSAU suggestion unit status
                         */
                        if ($appearanceCase->getUnitStatus() &&
                            $csauSuggestion->getUnitStatus()->isPending() &&
                            $unitStatus->isAccepted()
                        ) {
                            throw new ValidationException(
                                trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.csau.unit.pending'),
                                'appearance_cases.video_chat.unit_status_id'
                            );
                        }
                    }
                }
            }

            /**
             * Checking appearance case description status existence
             */
            if ($appearanceCase->getDescriptionStatus()) {

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $videoChat['description_status_id'] ?? null
                );

                /**
                 * Checking description status existence
                 */
                if (!$descriptionStatus) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.descriptionStatus'),
                        'appearance_cases.video_chat.description_status_id'
                    );
                }
            }

            /**
             * Checking appearance case platforms status existence
             */
            if ($appearanceCase->getPlatformsStatus()) {

                /**
                 * Getting platforms status
                 */
                $platformsStatus = RequestFieldStatusList::getItem(
                    $videoChat['platforms_status_id'] ?? null
                );

                /**
                 * Checking platforms status existence
                 */
                if (!$platformsStatus) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.platformsStatus'),
                        'appearance_cases.video_chat.platforms_status_id'
                    );
                }
            }
        }

        /**
         * Checking real life existence
         */
        if (isset($appearanceCasesStatuses['real_life'])) {

            /**
             * Getting real life
             */
            $realLife = $appearanceCasesStatuses['real_life'];

            /** @var VybeChangeRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybeChangeRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getRealLife()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if (!$appearanceCase) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybeChangeRequest.' . __FUNCTION__ . '.realLife'),
                    'appearance_cases.real_life.absence'
                );
            }

            /**
             * Getting CSAU suggestion
             */
            $csauSuggestion = $appearanceCase->csauSuggestion;

            /**
             * Getting an appearance case price status existence
             */
            if ($appearanceCase->getPriceStatus()) {

                /**
                 * Getting price status
                 */
                $priceStatus = RequestFieldStatusList::getItem(
                    $realLife['price_status_id'] ?? null
                );

                /**
                 * Checking price status existence
                 */
                if ($appearanceCase->getPriceStatus()->isPending() &&
                    !$priceStatus
                ) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.priceStatus'),
                        'appearance_cases.real_life.price_status_id'
                    );
                }
            }

            /**
             * Getting an appearance case unit status existence
             */
            if ($appearanceCase->getUnitStatus()) {

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $realLife['unit_status_id'] ?? null
                );

                /**
                 * Checking unit status existence
                 */
                if ($appearanceCase->getUnitStatus()->isPending() &&
                    !$unitStatus
                ) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.unitStatus'),
                        'appearance_cases.real_life.unit_status_id'
                    );
                } else {

                    /**
                     * Checking CSAU suggestion existence
                     */
                    if ($csauSuggestion) {

                        /**
                         * Checking CSAU suggestion unit status
                         */
                        if ($appearanceCase->getUnitStatus() &&
                            $csauSuggestion->getUnitStatus()->isPending() &&
                            $unitStatus->isAccepted()
                        ) {
                            throw new ValidationException(
                                trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.csau.unit.pending'),
                                'appearance_cases.real_life.unit_status_id'
                            );
                        }
                    }
                }
            }

            /**
             * Checking appearance case description status existence
             */
            if ($appearanceCase->getDescriptionStatus()) {

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $realLife['description_status_id'] ?? null
                );

                /**
                 * Checking description status existence
                 */
                if (!$descriptionStatus) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.descriptionStatus'),
                        'appearance_cases.real_life.description_status_id'
                    );
                }
            }

            /**
             * Checking appearance case city place status existence
             */
            if ($appearanceCase->getCityPlaceStatus()) {

                /**
                 * Getting city place status
                 */
                $cityPlaceStatus = RequestFieldStatusList::getItem(
                    $realLife['city_place_status_id'] ?? null
                );

                /**
                 * Checking city place status existence
                 */
                if (!$cityPlaceStatus) {
                    throw new ValidationException(
                        trans('exceptions/service/vybeChangeRequest.' . __FUNCTION__ . '.cityPlaceStatus'),
                        'appearance_cases.real_life.city_place_status_id'
                    );
                }
            }
        }

        return true;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $appearanceCases
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateAppearanceCasesStatuses(
        VybeChangeRequest $vybeChangeRequest,
        array $appearanceCases
    ) : Collection
    {
        /**
         * Preparing vybe change request appearance cases collection
         */
        $vybeChangeRequestAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /** @var VybeChangeRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybeChangeRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVoiceChat()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting price status
                 */
                $priceStatus = RequestFieldStatusList::getItem(
                    $voiceChat['price_status_id'] ?? null
                );

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $voiceChat['unit_status_id'] ?? null
                );

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $voiceChat['description_status_id'] ?? null
                );

                /**
                 * Getting platforms status
                 */
                $platformsStatus = RequestFieldStatusList::getItem(
                    $voiceChat['platforms_status_id'] ?? null
                );

                /**
                 * Updating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->updateStatuses(
                    $appearanceCase,
                    $priceStatus,
                    $unitStatus,
                    $descriptionStatus,
                    $platformsStatus,
                    null
                );

                /**
                 * Adding vybe change request appearance case to a collection
                 */
                $vybeChangeRequestAppearanceCases->push(
                    $vybeChangeRequestAppearanceCase
                );
            }
        }

        /**
         * Checking video chat existence
         */
        if (isset($appearanceCases['video_chat'])) {

            /**
             * Getting video chat
             */
            $videoChat = $appearanceCases['video_chat'];

            /** @var VybeChangeRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybeChangeRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVideoChat()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting price status
                 */
                $priceStatus = RequestFieldStatusList::getItem(
                    $videoChat['price_status_id'] ?? null
                );

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $videoChat['unit_status_id'] ?? null
                );

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $videoChat['description_status_id'] ?? null
                );

                /**
                 * Getting platforms status
                 */
                $platformsStatus = RequestFieldStatusList::getItem(
                    $videoChat['platforms_status_id'] ?? null
                );

                /**
                 * Updating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->updateStatuses(
                    $appearanceCase,
                    $priceStatus,
                    $unitStatus,
                    $descriptionStatus,
                    $platformsStatus,
                    null
                );

                /**
                 * Adding vybe change request appearance case to a collection
                 */
                $vybeChangeRequestAppearanceCases->push(
                    $vybeChangeRequestAppearanceCase
                );
            }
        }

        /**
         * Checking real life existence
         */
        if (isset($appearanceCases['real_life'])) {

            /**
             * Getting real life
             */
            $realLife = $appearanceCases['real_life'];

            /** @var VybeChangeRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybeChangeRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getRealLife()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting price status
                 */
                $priceStatus = RequestFieldStatusList::getItem(
                    $realLife['price_status_id'] ?? null
                );

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $realLife['unit_status_id'] ?? null
                );

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $realLife['description_status_id'] ?? null
                );

                /**
                 * Getting city place status
                 */
                $cityPlaceStatus = RequestFieldStatusList::getItem(
                    $realLife['city_place_status_id'] ?? null
                );

                /**
                 * Updating vybe change request appearance case
                 */
                $vybeChangeRequestAppearanceCase = $this->vybeChangeRequestAppearanceCaseRepository->updateStatuses(
                    $appearanceCase,
                    $priceStatus,
                    $unitStatus,
                    $descriptionStatus,
                    null,
                    $cityPlaceStatus
                );

                /**
                 * Adding vybe change request appearance case to a collection
                 */
                $vybeChangeRequestAppearanceCases->push(
                    $vybeChangeRequestAppearanceCase
                );
            }
        }

        return $vybeChangeRequestAppearanceCases;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $declinedImagesIds
     * @param array $declinedVideosIds
     *
     * @return RequestStatusListItem
     */
    public function getRequestStatus(
        VybeChangeRequest $vybeChangeRequest,
        array $declinedImagesIds,
        array $declinedVideosIds
    ) : RequestStatusListItem
    {
        /**
         * Checking declined images or videos existence
         */
        if (!empty($declinedImagesIds) ||
            !empty($declinedVideosIds)
        ) {
            return RequestStatusList::getDeclinedItem();
        }

        /**
         * Checking all vybe change request statuses
         */
        if (($vybeChangeRequest->getTitleStatus() && $vybeChangeRequest->getTitleStatus()->isDeclined()) ||
            ($vybeChangeRequest->getCategoryStatus() && $vybeChangeRequest->getCategoryStatus()->isDeclined()) ||
            ($vybeChangeRequest->getSubcategoryStatus() && $vybeChangeRequest->getSubcategoryStatus()->isDeclined()) ||
            ($vybeChangeRequest->getDevicesStatus() && $vybeChangeRequest->getDevicesStatus()->isDeclined()) ||
            ($vybeChangeRequest->getActivityStatus() && $vybeChangeRequest->getActivityStatus()->isDeclined()) ||
            ($vybeChangeRequest->getPeriodStatus() && $vybeChangeRequest->getPeriodStatus()->isDeclined()) ||
            ($vybeChangeRequest->getUserCountStatus() && $vybeChangeRequest->getUserCountStatus()->isDeclined()) ||
            ($vybeChangeRequest->getSchedulesStatus() && $vybeChangeRequest->getSchedulesStatus()->isDeclined()) ||
            ($vybeChangeRequest->getAccessStatus() && $vybeChangeRequest->getAccessStatus()->isDeclined()) ||
            ($vybeChangeRequest->getShowcaseStatus() && $vybeChangeRequest->getShowcaseStatus()->isDeclined()) ||
            ($vybeChangeRequest->getOrderAcceptStatus() && $vybeChangeRequest->getOrderAcceptStatus()->isDeclined()) ||
            ($vybeChangeRequest->getStatusStatus() && $vybeChangeRequest->getStatusStatus()->isDeclined())
        ) {
            return RequestStatusList::getDeclinedItem();
        }

        /**
         * Getting vybe type
         */
        $vybeType = $this->vybeService->getVybeTypeByParameters(
            $vybeChangeRequest->getPeriod() ?
                $vybeChangeRequest->getPeriod() :
                $vybeChangeRequest->vybe->getPeriod(),
            $vybeChangeRequest->user_count ?
                $vybeChangeRequest->user_count :
                $vybeChangeRequest->vybe->user_count
        );

        /**
         * Checking vybe change request type
         */
        if (!$vybeType->isEvent()) {
            if (($vybeChangeRequest->getOrderAdvanceStatus() &&
                $vybeChangeRequest->getOrderAdvanceStatus()->isDeclined())
            ) {
                return RequestStatusList::getDeclinedItem();
            }
        }

        /** @var VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase */
        foreach ($vybeChangeRequest->appearanceCases as $vybeChangeRequestAppearanceCase) {

            /**
             * Checking vybe change request appearance case price and unit statuses
             */
            if (($vybeChangeRequestAppearanceCase->getPriceStatus() && $vybeChangeRequestAppearanceCase->getPriceStatus()->isDeclined()) ||
                ($vybeChangeRequestAppearanceCase->getUnitStatus() && $vybeChangeRequestAppearanceCase->getUnitStatus()->isDeclined())
            ) {
                return RequestStatusList::getDeclinedItem();
            }

            /**
             * Checking vybe change request appearance case description status
             */
            if ($vybeChangeRequestAppearanceCase->getDescriptionStatus()) {
                if ($vybeChangeRequestAppearanceCase->getDescriptionStatus()->isDeclined()) {
                    return RequestStatusList::getDeclinedItem();
                }
            }

            /**
             * Checking vybe change request appearance case platforms status
             */
            if ($vybeChangeRequestAppearanceCase->getPlatformsStatus()) {
                if ($vybeChangeRequestAppearanceCase->getPlatformsStatus()->isDeclined()) {
                    return RequestStatusList::getDeclinedItem();
                }
            }

            /**
             * Checking vybe change request appearance case location status
             */
            if ($vybeChangeRequestAppearanceCase->getCityPlaceStatus()) {
                if ($vybeChangeRequestAppearanceCase->getCityPlaceStatus()->isDeclined()) {
                    return RequestStatusList::getDeclinedItem();
                }
            }
        }

        return RequestStatusList::getAcceptedItem();
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function deleteAllForChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void
    {
        /**
         * Deleting vybe change request appearance cases
         */
        $this->vybeChangeRequestAppearanceCaseRepository->deleteForVybeChangeRequest(
            $vybeChangeRequest
        );

        /**
         * Deleting vybe publish request schedules
         */
        $this->vybeChangeRequestScheduleRepository->deleteForVybeChangeRequest(
            $vybeChangeRequest
        );

        /**
         * Deleting vybe change request
         */
        $this->vybeChangeRequestRepository->delete(
            $vybeChangeRequest
        );
    }

    /**
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCaseStatus(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
    ) : RequestFieldStatusListItem
    {
        $statusesIds = [];

        if ($vybeChangeRequestAppearanceCase->getPriceStatus()) {
            if ($vybeChangeRequestAppearanceCase->getPriceStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybeChangeRequestAppearanceCase->getPriceStatus()->id;
            }
        }

        if ($vybeChangeRequestAppearanceCase->getUnitStatus()) {
            if ($vybeChangeRequestAppearanceCase->getUnitStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybeChangeRequestAppearanceCase->getUnitStatus()->id;
            }
        }

        if ($vybeChangeRequestAppearanceCase->getDescriptionStatus()) {
            if ($vybeChangeRequestAppearanceCase->getDescriptionStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybeChangeRequestAppearanceCase->getDescriptionStatus()->id;
            }
        }

        if ($vybeChangeRequestAppearanceCase->getPlatformsStatus()) {
            if ($vybeChangeRequestAppearanceCase->getPlatformsStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybeChangeRequestAppearanceCase->getPlatformsStatus()->id;
            }
        }

        if ($vybeChangeRequestAppearanceCase->getCityPlaceStatus()) {
            if ($vybeChangeRequestAppearanceCase->getCityPlaceStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybeChangeRequestAppearanceCase->getCityPlaceStatus()->id;
            }
        }

        foreach ($statusesIds as $statusId) {
            if (RequestFieldStatusList::getItem($statusId)->isPending()) {
                return RequestFieldStatusList::getPendingItem();
            }
        }

        return RequestFieldStatusList::getAcceptedItem();
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCasesStatus(
        VybeChangeRequest $vybeChangeRequest
    ) : RequestFieldStatusListItem
    {
        $statusesIds = [];

        foreach ($vybeChangeRequest->appearanceCases as $vybeChangeRequestAppearanceCase) {
            $requestFieldStatus = $this->getAppearanceCaseStatus(
                $vybeChangeRequestAppearanceCase
            );

            if ($requestFieldStatus->isDeclined()) {
                return $requestFieldStatus;
            } else {
                $statusesIds[] = $requestFieldStatus->id;
            }
        }

        foreach ($statusesIds as $statusId) {
            if (RequestFieldStatusList::getItem($statusId)->isPending()) {
                return RequestFieldStatusList::getPendingItem();
            }
        }

        return RequestFieldStatusList::getAcceptedItem();
    }

    /**
     * @param Collection|null $vybeChangeRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeChangeRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking vybe change requests existence
             */
            if ($vybeChangeRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeChangeRequestRepository->getRequestStatusCountByIds(
                    $vybeChangeRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->vybeChangeRequestRepository->getRequestStatusCount(
                    $requestStatusListItem
                );
            }

            /**
             * Setting count
             */
            $requestStatusListItem->setCount($count);

            /**
             * Adding request status to a response collection
             */
            $requestStatuses->add($requestStatusListItem);
        }

        return $requestStatuses;
    }

    /**
     * @param Collection $images
     * @param array $acceptedImagesIds
     * @param array $declinedImagesIds
     *
     * @throws ValidationException
     */
    public function checkImagesAreProcessed(
        Collection $images,
        array $acceptedImagesIds,
        array $declinedImagesIds
    ) : void
    {
        $imagesIds = $images->pluck('id')
            ->values()
            ->toArray();

        if ($imagesIds) {
            $imagesIds = array_diff($imagesIds, $acceptedImagesIds);

            if ($imagesIds) {
                $imagesIds = array_diff($imagesIds, $declinedImagesIds);

                if (!empty($imagesIds)) {

                    /** @var VybeImageResponse $image */
                    foreach ($images as $image) {
                        if (in_array($image->id, $imagesIds)) {
                            throw new ValidationException(
                                trans('exceptions/service/vybe/vybeChangeRequest.' . __FUNCTION__ . '.image'),
                                'images.' . $image->id
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Collection $videos
     * @param array $acceptedVideosIds
     * @param array $declinedVideosIds
     *
     * @throws ValidationException
     */
    public function checkVideosAreProcessed(
        Collection $videos,
        array $acceptedVideosIds,
        array $declinedVideosIds
    ) : void
    {
        $videosIds = $videos->pluck('id')
            ->values()
            ->toArray();

        if ($videosIds) {
            $videosIds = array_diff($videosIds, $acceptedVideosIds);

            if ($videosIds) {
                $videosIds = array_diff($videosIds, $declinedVideosIds);

                if (!empty($videosIds)) {

                    /** @var VybeVideoResponse $video */
                    foreach ($videos as $video) {
                        if (in_array($video->id, $videosIds)) {
                            throw new ValidationException(
                                trans('exceptions/service/vybe/vybeChangeRequest.' . __FUNCTION__ . '.video'),
                                'videos.' . $video->id
                            );
                        }
                    }
                }
            }
        }
    }
}
