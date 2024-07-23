<?php

namespace App\Services\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Lists\Gender\GenderList;
use App\Lists\Language\LanguageList;
use App\Lists\Unit\Type\UnitTypeList;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Step\VybeStepListItem;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestSchedule;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Category;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Sale;
use App\Models\MySql\Schedule;
use App\Models\MySql\Timeslot;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Repositories\Unit\UnitRepository;
use App\Repositories\Vybe\VybeAppearanceCaseSupportRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Repositories\Vybe\VybeSupportRepository;
use App\Services\File\FileService;
use App\Services\File\MediaService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Place\CityPlaceService;
use App\Services\Timeslot\TimeslotService;
use App\Services\Vybe\Interfaces\VybeServiceInterface;
use App\Settings\User\DaysThatVybesCanBeOrderedSetting;
use App\Settings\User\MaximumNumberOfUsersSetting;
use App\Transformers\Services\Vybe\GenderTransformer;
use App\Transformers\Services\Vybe\LanguageListItemTransformer;
use App\Transformers\Services\Vybe\PersonalityTraitListItemTransformer;
use App\Transformers\Services\Vybe\VybeAppearanceTransformer;
use App\Transformers\TransformTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dedicated\GoogleTranslate\TranslateException;
use Illuminate\Database\Eloquent\Collection;
use App\Settings\User\HandlingFeesSetting as UserHandlingFeesSetting;
use App\Settings\Vybe\HandlingFeesSetting as VybeHandlingFeesSetting;
use Illuminate\Support\Facades\Crypt;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;

/**
 * Class VybeService
 *
 * @package App\Services\Vybe
 */
class VybeService implements VybeServiceInterface
{
    use TransformTrait;

    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var CityPlaceRepository
     */
    protected CityPlaceRepository $cityPlaceRepository;

    /**
     * @var CityPlaceService
     */
    protected CityPlaceService $cityPlaceService;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var VybeAppearanceCaseSupportRepository
     */
    protected VybeAppearanceCaseSupportRepository $vybeAppearanceCaseSupportRepository;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeSupportRepository
     */
    protected VybeSupportRepository $vybeSupportRepository;

    /**
     * @var UserHandlingFeesSetting
     */
    protected UserHandlingFeesSetting $userHandlingFeesSetting;

    /**
     * @var VybeHandlingFeesSetting
     */
    protected VybeHandlingFeesSetting $vybeHandlingFeesSetting;

    /**
     * @var MaximumNumberOfUsersSetting
     */
    protected MaximumNumberOfUsersSetting $maximumNumberOfUsersSetting;

    /**
     * @var DaysThatVybesCanBeOrderedSetting
     */
    protected DaysThatVybesCanBeOrderedSetting $daysThatVybesCanBeOrderedSetting;

    /**
     * VybeService constructor
     */
    public function __construct()
    {
        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var CityPlaceService cityPlaceService */
        $this->cityPlaceService = new CityPlaceService();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var FileService fileService */
        $this->fileService = new FileService();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var VybeAppearanceCaseSupportRepository vybeAppearanceCaseSupportRepository */
        $this->vybeAppearanceCaseSupportRepository = new VybeAppearanceCaseSupportRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeSupportRepository vybeSupportRepository */
        $this->vybeSupportRepository = new VybeSupportRepository();

        /** @var UserHandlingFeesSetting userHandlingFeesSetting */
        $this->userHandlingFeesSetting = new UserHandlingFeesSetting();

        /** @var VybeHandlingFeesSetting vybeHandlingFeesSetting */
        $this->vybeHandlingFeesSetting = new VybeHandlingFeesSetting();

        /** @var MaximumNumberOfUsersSetting maximumNumberOfUsersSetting */
        $this->maximumNumberOfUsersSetting = new MaximumNumberOfUsersSetting();

        /** @var DaysThatVybesCanBeOrderedSetting daysThatVybesCanBeOrderedSetting */
        $this->daysThatVybesCanBeOrderedSetting = new DaysThatVybesCanBeOrderedSetting();
    }

    /**
     * @param Category $category
     *
     * @return int
     */
    public function countCategoryActivities(
        Category $category
    ) : int
    {
        $activitiesQuantity = count($category->activities);

        /** @var Category $subcategory */
        foreach ($category->subcategories as $subcategory) {
            $activitiesQuantity = $activitiesQuantity + count($subcategory->activities);
        }

        return $activitiesQuantity;
    }

    /**
     * @param Collection $vybes
     *
     * @return array
     */
    public function getFiltersForCatalog(
        Collection $vybes
    ) : array
    {
        $filterAppearanceCasesIds = [];
        $filterGendersIds = [];
        $filterPlatformsIds = [];
        $filterPersonalityTraitsIds = [];
        $filterLanguagesIds = [];
        $filterDevicesIds = [];
        $filterTagsIds = [];
        $filterUnitsIds = [];
        $filterCitiesIds = [];
        $filterPriceMax = null;
        $filterPriceMin = null;
        $filterAgeMin = null;
        $filterAgeMax = null;
        $prices = [];
        $ages = [];

        foreach ($vybes as $vybe) {
            if ($vybe->appearanceCases) {
                foreach ($vybe->appearanceCases as $appearanceCase) {

                    /** Collecting appearance cases ids */
                    if (!in_array($appearanceCase->appearance_id, $filterAppearanceCasesIds)) {
                        $filterAppearanceCasesIds[] = $appearanceCase->appearance_id;
                    }

                    /** Collecting city ids */
                    if ($appearanceCase->city_id) {
                        if (!in_array($appearanceCase->city_id, $filterCitiesIds)) {
                            $filterCitiesIds[] = $appearanceCase->city_id;
                        }
                    }

                    /** Collecting platforms ids */
                    if ($appearanceCase->platforms) {
                        foreach ($appearanceCase->platforms as $platform) {
                            if (!in_array($platform->id, $filterPlatformsIds)) {
                                $filterPlatformsIds[] = $platform->id;
                            }
                        }
                    }

                    /** Collecting prices */
                    if ($appearanceCase->price) {
                        $prices[] = $appearanceCase->price;
                    }
                }
            }

            /**
             * Collecting device ids
             */
            if ($vybe->devices) {
                foreach ($vybe->devices as $device) {
                    if (!in_array($device->id, $filterDevicesIds)) {
                        $filterDevicesIds[] = $device->id;
                    }

                }
            }

            if ($vybe->user) {
                /** Collecting genders ids */
                if (!in_array($vybe->user->gender_id, $filterGendersIds)) {
                    $filterGendersIds[] = $vybe->user->gender_id;
                }

                /** Collecting ages */
                $age = Carbon::now()->diffInYears($vybe->user->birth_date);
                if (!in_array($age, $ages)) {
                    $ages[] = $age;
                }
            }

            /**
             * Collecting personality traits ids
             */
            if ($vybe->user->personalityTraits) {
                foreach ($vybe->user->personalityTraits as $personalityTrait) {
                    if (!in_array($personalityTrait->trait_id, $filterPersonalityTraitsIds)) {
                        $filterPersonalityTraitsIds[] = $personalityTrait->trait_id;
                    }
                }
            }

            /**
             * Collecting language ids
             */
            if ($vybe->user->languages) {
                foreach ($vybe->user->languages as $language) {
                    if (!in_array($language->language_id, $filterLanguagesIds))
                        $filterLanguagesIds[] = $language->language_id; // look for language_id ???
                }
            }

            if ($vybe->activity) {
                /**
                 * Collecting tags ids
                 */
                if ($vybe->activity->tags) {
                    foreach ($vybe->activity->tags as $tag) {
                        if (!in_array($tag->id, $filterTagsIds)) {
                            $filterTagsIds[] = $tag->id;
                        }
                    }
                }

                /**
                 * Collecting unit ids
                 */
                if ($vybe->activity->units) {
                    foreach ($vybe->activity->units as $unit) {
                        if (!in_array($unit->id, $filterUnitsIds)) {
                            $filterUnitsIds[] = $unit->id;
                        }
                    }
                }
            }
        }

        /** Max and min prices */
        if ($prices) {
            $filterPriceMax = max($prices);
            $filterPriceMin = min($prices);
        }

        /** Max and min ages */
        if ($ages) {
            $filterAgeMax = max($ages);
            $filterAgeMin = min($ages);
        }

        $form = [];

        /**
         * Getting an appearance cases list
         */
        $form += $this->transformCollection(
            VybeAppearanceList::getItemsByIds(
                $filterAppearanceCasesIds
            ),
            new VybeAppearanceTransformer
        );

        /**
         * Getting a genders list
         */
        $form += $this->transformCollection(
            GenderList::getItemsByIds(
                $filterGendersIds
            ),
            new GenderTransformer
        );

        /**
         * Getting a language list
         */
        $form += $this->transformCollection(
            LanguageList::getItemsByIds(
                $filterLanguagesIds
            ),
            new LanguageListItemTransformer
        );

        /**
         * Getting a personality traits list
         */
        $form += $this->transformCollection(
            PersonalityTraitList::getItemsByIds(
                $filterPersonalityTraitsIds
            ),
            new PersonalityTraitListItemTransformer
        );

        $form['platforms_ids'] = $filterPlatformsIds;
        $form['devices_ids'] = $filterDevicesIds;
        $form['tags_ids'] = $filterTagsIds;
        $form['units_ids'] = $filterUnitsIds;
        $form['cities_ids'] = $filterCitiesIds;
        $form['price_max'] = $filterPriceMax;
        $form['price_min'] = $filterPriceMin;
        $form['age_max'] = $filterAgeMax;
        $form['age_min'] = $filterAgeMin;

        return $form;
    }

    /**
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getActivitiesForCatalog(
        Collection $vybes
    ): Collection
    {
        $activities = new Collection();

        /** @var Vybe $vybe */
        foreach ($vybes as $vybe) {
            $activities->add(
                $vybe->activity
            );
        }

        return $activities;
    }

    /**
     * @param array $files
     *
     * @throws BaseException
     */
    public function validateVybeAlbum(
        array $files
    ): void
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        if (isset($sortedFiles['images'])) {

            /** @var array $image */
            foreach ($sortedFiles['images'] as $image) {
                $this->mediaService->validateVybeImage(
                    $image['content'],
                    $image['mime']
                );
            }
        }

        if (isset($sortedFiles['videos'])) {

            /** @var array $video */
            foreach ($sortedFiles['videos'] as $video) {
                $this->mediaService->validateVybeVideo(
                    $video['content'],
                    $video['mime']
                );
            }
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkIsOnPublishRequest(
        Vybe $vybe
    ): bool
    {
        /**
         * Getting pending vybe publish request for vybe
         */
        if ($this->vybePublishRequestRepository->findPendingForVybe(
            $vybe
        )) {
            return true;
        }

        return false;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateFromVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ): Vybe
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById(
            $vybeChangeRequest->vybe->id
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->update(
            $vybe,
            $vybeChangeRequest->activity ?
                $vybeChangeRequest->activity :
                null,
            $vybeChangeRequest->getType() ?
                $vybeChangeRequest->getType() :
                null,
            $vybeChangeRequest->getPeriod() ?
                $vybeChangeRequest->getPeriod() :
                null,
            $vybeChangeRequest->getAccess() ?
                $vybeChangeRequest->getAccess() :
                null,
            $vybeChangeRequest->getShowcase() ?
                $vybeChangeRequest->getShowcase() :
                null,
            $vybeChangeRequest->getStatus() ?
                $vybeChangeRequest->getStatus() :
                null,
            $vybeChangeRequest->getAgeLimit() ?
                $vybeChangeRequest->getAgeLimit() :
                null,
            $vybeChangeRequest->getOrderAccept() ?
                $vybeChangeRequest->getOrderAccept() :
                null,
            $vybeChangeRequest->title ?
                $vybeChangeRequest->title :
                null,
            $vybeChangeRequest->user_count ?
                $vybeChangeRequest->user_count :
                null,
            $vybeChangeRequest->order_advance ?
                $vybeChangeRequest->order_advance :
                null,
            $vybeChangeRequest->images_ids ?
                $vybeChangeRequest->images_ids :
                null,
            $vybeChangeRequest->videos_ids ?
                $vybeChangeRequest->videos_ids :
                null
        );

        /**
         * Checking device ids existence
         */
        if (!is_null($vybeChangeRequest->devices_ids)) {

            /**
             * Attaching devices to vybe
             */
            $this->vybeRepository->attachDevices(
                $vybe,
                $vybeChangeRequest->devices_ids,
                true
            );
        }

        /** @var VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase */
        foreach ($vybeChangeRequest->appearanceCases as $vybeChangeRequestAppearanceCase) {

            /**
             * Getting vybe appearance case
             */
            $vybeAppearanceCase = $this->appearanceCaseRepository->findForVybeByAppearance(
                $vybeChangeRequest->vybe,
                $vybeChangeRequestAppearanceCase->getAppearance()
            );

            /**
             * Checking appearance case existence
             */
            if ($vybeAppearanceCase) {

                /**
                 * Updating vybe appearance case
                 */
                $vybeAppearanceCase = $this->appearanceCaseRepository->update(
                    $vybeAppearanceCase,
                    null,
                    null,
                    $vybeChangeRequestAppearanceCase->unit ?
                        $vybeChangeRequestAppearanceCase->unit :
                        null,
                    $vybeChangeRequestAppearanceCase->cityPlace ?
                        $vybeChangeRequestAppearanceCase->cityPlace :
                        null,
                    $vybeChangeRequestAppearanceCase->price ?
                        $vybeChangeRequestAppearanceCase->price :
                        null,
                    $vybeChangeRequestAppearanceCase->description ?
                        $vybeChangeRequestAppearanceCase->description :
                        null,
                    $vybeChangeRequestAppearanceCase->same_location ?
                        $vybeChangeRequestAppearanceCase->same_location :
                        null,
                    $vybeChangeRequestAppearanceCase->enabled ?
                        $vybeChangeRequestAppearanceCase->enabled :
                        null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $vybeAppearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    $vybeChangeRequestAppearanceCase->getAppearance(),
                    $vybeChangeRequestAppearanceCase->unit,
                    $vybeChangeRequestAppearanceCase->cityPlace,
                    $vybeChangeRequestAppearanceCase->price,
                    $vybeChangeRequestAppearanceCase->description,
                    $vybeChangeRequestAppearanceCase->same_location,
                    $vybeChangeRequestAppearanceCase->enabled
                );
            }

            /**
             * Checking platforms existence
             */
            if ($vybeChangeRequestAppearanceCase->platforms_ids) {

                /**
                 * Attaching platforms to vybe
                 */
                $this->appearanceCaseRepository->attachPlatforms(
                    $vybeAppearanceCase,
                    $vybeChangeRequestAppearanceCase->platforms_ids,
                    true
                );
            }
        }

        /**
         * Checking vybe type
         */
        if (!$vybe->getType()->isSolo()) {
            $vybe->appearanceCases()
                ->whereNotIn(
                    'appearance_id', $vybeChangeRequest->appearanceCases
                        ->pluck('appearance_id')
                        ->values()
                        ->toArray()
                )->delete();
        }

        /**
         * Checking vybe change request schedules status existence
         */
        if ($vybeChangeRequest->getSchedulesStatus()) {

            /**
             * Checking vybe change request schedules existence
             */
            if ($vybeChangeRequest->schedules->count() > 0) {

                /**
                 * Deleting vybe schedules
                 */
                $this->scheduleRepository->deleteForVybe(
                    $vybe
                );

                /** @var VybeChangeRequestSchedule $vybeChangeRequestSchedule */
                foreach ($vybeChangeRequest->schedules as $vybeChangeRequestSchedule) {

                    /**
                     * Creating vybe schedule
                     */
                    $this->scheduleRepository->store(
                        $vybe,
                        $vybeChangeRequestSchedule->datetime_from,
                        $vybeChangeRequestSchedule->datetime_to
                    );
                }
            }
        }

        /**
         * Updating vybe version
         */
        $vybe = $this->vybeRepository->increaseVersion(
            $vybe
        );

        return $this->vybeRepository->findFullById(
            $vybe->id
        );
    }

    /**
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(
        Vybe $vybe,
        User $user
    ): bool
    {
        return $vybe->user_id == $user->id;
    }

    /**
     * @param array $appearanceCases
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateAppearanceCases(
        array $appearanceCases
    ): bool
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
             * Checking possible doubling with a unit suggestion
             */
            if (!isset($voiceChat['unit_id'])) {
                if (!isset($voiceChat['unit_suggestion'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.unit.absence'),
                        'appearance_cases.voice_chat.unit_suggestion'
                    );
                }
            } else {
                if (isset($voiceChat['unit_suggestion'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.unit.doubling'),
                        'appearance_cases.voice_chat.unit_suggestion'
                    );
                }
            }
        }

        /**
         * Checking video chat existence
         */
        if (isset($appearanceCases['video_chat'])) {

            /**
             * Checking video chat existence
             */
            $videoChat = $appearanceCases['video_chat'];

            /**
             * Checking possible doubling with a unit suggestion
             */
            if (!isset($videoChat['unit_id'])) {
                if (!isset($videoChat['unit_suggestion'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.unit.absence'),
                        'appearance_cases.video_chat.unit_suggestion'
                    );
                }
            } else {
                if (isset($videoChat['unit_suggestion'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.unit.doubling'),
                        'appearance_cases.video_chat.unit_suggestion'
                    );
                }
            }
        }

        /**
         * Checking real life existence
         */
        if (isset($appearanceCases['real_life'])) {

            /**
             * Checking real life existence
             */
            $realLife = $appearanceCases['real_life'];

            /**
             * Checking possible doubling with a unit suggestion
             */
            if (!isset($realLife['unit_id'])) {
                if (!isset($realLife['unit_suggestion'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.unit.absence'),
                        'appearance_cases.real_life.unit_suggestion'
                    );
                }
            } else {
                if (isset($realLife['unit_suggestion'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.unit.doubling'),
                        'appearance_cases.real_life.unit_suggestion'
                    );
                }
            }

            /**
             * Checking the same location
             */
            if (isset($realLife['same_location'])) {

                /**
                 * Checking city id existence
                 */
                if (!isset($realLife['city_place_id'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.city.absence'),
                        'appearance_cases.real_life.city_place_id'
                    );
                }
            }
        }

        return true;
    }

    /**
     * @param VybeTypeListItem $vybeTypeListItem
     * @param array $schedules
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateSchedules(
        VybeTypeListItem $vybeTypeListItem,
        array $schedules
    ): bool
    {
        /**
         * Checking vybe type
         */
        if ($vybeTypeListItem->isEvent()) {

            /**
             * Checking event vybe has only one schedule
             */
            if (count($schedules) != 1) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.event.one'),
                    'schedules'
                );
            } else {

                /**
                 * @var int $key
                 * @var array $schedule
                 */
                foreach ($schedules as $key => $schedule) {

                    /**
                     * Checking date from existing
                     */
                    if (!isset($schedule['datetime_from'])) {
                        throw new ValidationException(
                            trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.datetime_from.find'),
                            'schedules.' . $key . '.datetime_from'
                        );
                    }

                    /**
                     * Checking date to exist
                     */
                    if (!isset($schedule['datetime_to'])) {
                        throw new ValidationException(
                            trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.datetime_to.find'),
                            'schedules.' . $key . '.datetime_to'
                        );
                    }
                }
            }
        } else {

            /**
             * @var int $key
             * @var array $schedule
             */
            foreach ($schedules as $key => $schedule) {

                /**
                 * Checking invalid variable exists in array
                 */
                if (!array_key_exists('datetime_from', $schedule)) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.datetime_from.invalid'),
                        'schedules.' . $key . '.datetime_from'
                    );
                }

                /**
                 * Checking invalid variable exists in array
                 */
                if (!array_key_exists('datetime_to', $schedule)) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.datetime_to.invalid'),
                        'schedules.' . $key . '.datetime_to'
                    );
                }
            }
        }

        return true;
    }

    /**
     * @param array $files
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateFiles(
        array $files
    ): bool
    {
        /**
         * Checking files allowed amount
         */
        if (count($files) > 5) {
            throw new ValidationException(
                trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.files.many'),
                'files'
            );
        }

        /**
         * @var int $key
         * @var array $file
         */
        foreach ($files as $key => $file) {

            /**
             * Checking is file a vybe image
             */
            if ($this->mediaService->isVybeImage(
                $file['mime']
            )) {

                try {

                    /**
                     * Validating vybe image
                     */
                    $this->mediaService->validateVybeImage(
                        $file['content'],
                        $file['mime']
                    );
                } catch (BaseException $exception) {
                    throw new ValidationException(
                        $exception->getHumanReadableMessage(),
                        'files.' . $key
                    );
                }

                /**
                 * Checking is file a vybe video
                 */
            } elseif ($this->mediaService->isVybeVideo(
                $file['mime']
            )) {
                try {

                    /**
                     * Validating vybe video
                     */
                    $this->mediaService->validateVybeVideo(
                        $file['content'],
                        $file['mime']
                    );
                } catch (BaseException $exception) {
                    throw new ValidationException(
                        $exception->getHumanReadableMessage(),
                        'files.' . $key
                    );
                }
            } else {
                throw new ValidationException(
                    trans('exceptions/service/vybe/draftVybe.' . __FUNCTION__ . '.files.undefined'),
                    'files.' . $key
                );
            }
        }

        return true;
    }

    /**
     * @param array|null $uploadFiles
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param array|null $deletedImagesIds
     * @param array|null $deletedVideosIds
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function checkFilesUploadAvailability(
        ?array $uploadFiles,
        ?array $imagesIds = null,
        ?array $videosIds = null,
        ?array $deletedImagesIds = null,
        ?array $deletedVideosIds = null
    ) : bool
    {
        $imagesIds = $imagesIds ?? [];
        $videosIds = $videosIds ?? [];
        $deletedImagesIds = $deletedImagesIds ?? [];
        $deletedVideosIds = $deletedVideosIds ?? [];
        $uploadFiles = $uploadFiles ?? [];

        $totalFilesCount = count(array_diff($imagesIds, $deletedImagesIds)) +
            count(array_diff($videosIds, $deletedVideosIds)) +
            count($uploadFiles);

        /**
         * Checking total files amount
         */
        if ($totalFilesCount > 5) {
            throw new ValidationException(
                trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.files.many'),
                'files'
            );
        } elseif ($totalFilesCount <= 0) {
            throw new ValidationException(
                trans('exceptions/service/vybe/vybe.' . __FUNCTION__ . '.files.absence'),
                'files'
            );
        }

        return true;
    }

    /**
     * @param array $files
     *
     * @return array
     */
    public function getImagesFromFiles(
        array $files
    ): array
    {
        $images = [];

        /** @var array $file */
        foreach ($files as $file) {

            /**
             * Checking is file a vybe image
             */
            if ($this->mediaService->isVybeImage(
                $file['mime']
            )) {
                $images[] = $file;
            }
        }

        return $images;
    }

    /**
     * @param array $files
     *
     * @return array
     */
    public function getVideosFromFiles(
        array $files
    ): array
    {
        $videos = [];

        /** @var array $file */
        foreach ($files as $file) {

            /**
             * Checking is file a vybe video
             */
            if ($this->mediaService->isVybeVideo(
                $file['mime']
            )) {
                $videos[] = $file;
            }
        }

        return $videos;
    }

    /**
     * @param VybePeriodListItem $vybePeriodListItem
     * @param int $userCount
     *
     * @return VybeTypeListItem
     */
    public function getVybeTypeByParameters(
        VybePeriodListItem $vybePeriodListItem,
        int $userCount
    ): VybeTypeListItem
    {
        /**
         * Checking vybe period
         */
        if ($vybePeriodListItem->isOneTime()) {
            return VybeTypeList::getEventItem();
        } else {

            /**
             * Checking user count
             */
            if ($userCount > 1) {
                return VybeTypeList::getGroupItem();
            } else {
                return VybeTypeList::getSoloItem();
            }
        }
    }

    /**
     * @param array|null $imagesIds
     * @param array|null $deletedImagesIds
     * @param array|null $uploadedImagesIds
     *
     * @return array
     */
    public function getChangedMediaIds(
        ?array $imagesIds,
        ?array $deletedImagesIds,
        ?array $uploadedImagesIds
    ): array
    {
        return array_merge(
            array_diff(
                !is_null($imagesIds) ? $imagesIds : [],
                !is_null($deletedImagesIds) ? $deletedImagesIds : []
            ), !is_null($uploadedImagesIds) ? $uploadedImagesIds : []
        );
    }

    /**
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function deleteAppearanceCasesForVybe(
        Vybe $vybe
    ): void
    {
        /** @var AppearanceCase $appearanceCase */
        foreach ($vybe->appearanceCases as $appearanceCase) {

            /**
             * Deleting vybe appearance case support
             */
            $this->vybeAppearanceCaseSupportRepository->deleteForAppearanceCase(
                $appearanceCase
            );
        }

        /**
         * Deleting existing vybe appearance cases
         */
        $this->appearanceCaseRepository->deleteForceForVybe(
            $vybe
        );
    }

    /**
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function deleteAllVybeSupport(
        Vybe $vybe
    ): void
    {
        /**
         * Deleting vybe support
         */
        $this->vybeSupportRepository->deleteForVybe(
            $vybe
        );

        /** @var AppearanceCase $appearanceCase */
        foreach ($vybe->appearanceCases as $appearanceCase) {

            /**
             * Deleting vybe appearance case support
             */
            $this->vybeAppearanceCaseSupportRepository->deleteForAppearanceCase(
                $appearanceCase
            );
        }
    }

    /**
     * @param Vybe $vybe
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
        Vybe $vybe,
        array $appearanceCases
    ) : Collection
    {
        /**
         * Preparing vybe appearance cases collection
         */
        $responseAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $voiceChat['unit_id'] ?? null
            );

            /**
             * Creating vybe appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->store(
                $vybe,
                VybeAppearanceList::getVoiceChat(),
                $unit,
                null,
                $voiceChat['price'],
                $voiceChat['description'] ?? null,
                null,
                $voiceChat['enabled']
            );

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $voiceChat['platforms_ids'] ?? []
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray()
                    );
                }

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Creating vybe appearance case support
                     */
                    $this->vybeAppearanceCaseSupportRepository->store(
                        $appearanceCase,
                        $voiceChat['unit_suggestion'],
                        $vybe->support->activity_suggestion ?
                            ($voiceChat['platforms_ids'] ?? null) :
                            null
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $videoChat['unit_id'] ?? null
            );

            /**
             * Creating vybe appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->store(
                $vybe,
                VybeAppearanceList::getVideoChat(),
                $unit,
                null,
                $videoChat['price'],
                $videoChat['description'] ?? null,
                null,
                $videoChat['enabled']
            );

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $videoChat['platforms_ids'] ?? null
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray()
                    );
                }

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Creating vybe appearance case support
                     */
                    $this->vybeAppearanceCaseSupportRepository->store(
                        $appearanceCase,
                        $videoChat['unit_suggestion'],
                        $vybe->support->activity_suggestion ?
                            ($videoChat['platforms_ids'] ?? []) :
                            null
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $realLife['unit_id'] ?? null
            );

            /**
             * Preparing city place variable
             */
            $cityPlace = null;

            /**
             * Checking the same location existence
             */
            if ($realLife['same_location'] === true) {

                /**
                 * Checking user current city place existence
                 */
                if ($vybe->user->currentCityPlace) {

                    /**
                     * Getting user current city place
                     */
                    $cityPlace = $vybe->user->currentCityPlace;
                }
            } else {

                /**
                 * Getting city place
                 */
                $cityPlace = $this->cityPlaceRepository->findByPlaceId(
                    $realLife['city_place_id'] ?? null
                );
            }

            /**
             * Checking city place existence
             */
            if (!$cityPlace) {

                /**
                 * Creating city place
                 */
                $cityPlace = $this->cityPlaceService->getOrCreate(
                    $realLife['city_place_id']
                );
            }

            /**
             * Creating vybe appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->store(
                $vybe,
                VybeAppearanceList::getRealLife(),
                $unit,
                $cityPlace,
                $realLife['price'],
                $realLife['description'] ?? null,
                $realLife['same_location'],
                $realLife['enabled']
            );

            if ($appearanceCase) {

                /**
                 * Checking suggestion existence
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Creating vybe appearance case support
                     */
                    $this->vybeAppearanceCaseSupportRepository->store(
                        $appearanceCase,
                        $realLife['unit_suggestion']
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
                );
            }
        }

        return $responseAppearanceCases;
    }

    /**
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function createAppearanceCasesWithoutSuggestions(
        Vybe $vybe,
        array $appearanceCases
    ) : Collection
    {
        /**
         * Preparing vybe appearance cases collection
         */
        $responseAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $voiceChat['unit_id'] ?? null
            );

            /**
             * Checking unit existence
             */
            if (!$unit) {

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Getting unit
                     */
                    $unit = $this->unitRepository->findByName(
                        $voiceChat['unit_suggestion']['en']
                    );

                    /**
                     * Checking unit existence
                     */
                    if (!$unit) {

                        /**
                         * Creating unit
                         */
                        $unit = $this->unitRepository->store(
                            $vybe->getType() && $vybe->getType()->isEvent() ?
                                UnitTypeList::getEvent() :
                                UnitTypeList::getUsual(),
                            $voiceChat['unit_suggestion'],
                            null
                        );
                    }
                }
            }

            /**
             * Creating vybe appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->store(
                $vybe,
                VybeAppearanceList::getVoiceChat(),
                $unit,
                null,
                $voiceChat['price'],
                $voiceChat['description'] ?? null,
                null,
                $voiceChat['enabled']
            );

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $voiceChat['platforms_ids'] ?? []
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray()
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $videoChat['unit_id'] ?? null
            );

            /**
             * Checking unit existence
             */
            if (!$unit) {

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Getting unit
                     */
                    $unit = $this->unitRepository->findByName(
                        $videoChat['unit_suggestion']['en']
                    );

                    /**
                     * Checking unit existence
                     */
                    if (!$unit) {

                        /**
                         * Creating unit
                         */
                        $unit = $this->unitRepository->store(
                            $vybe->getType() && $vybe->getType()->isEvent() ?
                                UnitTypeList::getEvent() :
                                UnitTypeList::getUsual(),
                            $videoChat['unit_suggestion'],
                            null
                        );
                    }
                }
            }

            /**
             * Creating vybe appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->store(
                $vybe,
                VybeAppearanceList::getVideoChat(),
                $unit,
                null,
                $videoChat['price'],
                $videoChat['description'] ?? null,
                null,
                $videoChat['enabled']
            );

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $videoChat['platforms_ids'] ?? null
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray()
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $realLife['unit_id'] ?? null
            );

            /**
             * Checking unit existence
             */
            if (!$unit) {

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Getting unit
                     */
                    $unit = $this->unitRepository->findByName(
                        $realLife['unit_suggestion']['en']
                    );

                    /**
                     * Checking unit existence
                     */
                    if (!$unit) {

                        /**
                         * Creating unit
                         */
                        $unit = $this->unitRepository->store(
                            $vybe->getType() && $vybe->getType()->isEvent() ?
                                UnitTypeList::getEvent() :
                                UnitTypeList::getUsual(),
                            $realLife['unit_suggestion'],
                            null
                        );
                    }
                }
            }

            /**
             * Preparing city place variable
             */
            $cityPlace = null;

            /**
             * Checking the same location existence
             */
            if ($realLife['same_location'] === true) {

                /**
                 * Checking user current city place existence
                 */
                if ($vybe->user->currentCityPlace) {

                    /**
                     * Getting user current city place
                     */
                    $cityPlace = $vybe->user->currentCityPlace;
                }
            } else {

                /**
                 * Getting city place
                 */
                $cityPlace = $this->cityPlaceRepository->findByPlaceId(
                    $realLife['city_place_id'] ?? null
                );
            }

            /**
             * Checking city place existence
             */
            if (!$cityPlace) {

                /**
                 * Creating city place
                 */
                $cityPlace = $this->cityPlaceService->getOrCreate(
                    $realLife['city_place_id']
                );
            }

            /**
             * Creating vybe appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->store(
                $vybe,
                VybeAppearanceList::getRealLife(),
                $unit,
                $cityPlace,
                $realLife['price'],
                $realLife['description'] ?? null,
                $realLife['same_location'],
                $realLife['enabled']
            );

            if ($appearanceCase) {

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
                );
            }
        }

        return $responseAppearanceCases;
    }

    /**
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateAppearanceCases(
        Vybe $vybe,
        array $appearanceCases
    ): Collection
    {
        /**
         * Deleting removed appearance cases from an array
         */
        $this->deleteRemovedAppearanceCases(
            $vybe->appearanceCases,
            $appearanceCases
        );

        /**
         * Preparing vybe appearance cases collection
         */
        $responseAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $voiceChat['unit_id'] ?? null
            );

            /**
             * Getting a voice chat appearance case
             */
            $appearanceCase = $vybe->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVoiceChat()->id)
                ->first();

            /**
             * Checking appearance cases existence
             */
            if ($appearanceCase) {

                /**
                 * Updating appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->update(
                    $appearanceCase,
                    $vybe,
                    VybeAppearanceList::getVoiceChat(),
                    $unit,
                    null,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    null,
                    $voiceChat['enabled'] ?? null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    VybeAppearanceList::getVoiceChat(),
                    $unit,
                    null,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    null,
                    $voiceChat['enabled'] ?? null
                );
            }

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $voiceChat['platforms_ids'] ?? []
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray(),
                        true
                    );
                }

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Creating vybe appearance case support
                     */
                    $this->vybeAppearanceCaseSupportRepository->store(
                        $appearanceCase,
                        $voiceChat['unit_suggestion']
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $videoChat['unit_id'] ?? null
            );

            /**
             * Getting a video chat appearance case
             */
            $appearanceCase = $vybe->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVideoChat()->id)
                ->first();

            /**
             * Checking appearance cases existence
             */
            if ($appearanceCase) {

                /**
                 * Updating appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->update(
                    $appearanceCase,
                    $vybe,
                    VybeAppearanceList::getVideoChat(),
                    $unit,
                    null,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    null,
                    $voiceChat['enabled'] ?? null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    VybeAppearanceList::getVideoChat(),
                    $unit,
                    null,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    null,
                    $voiceChat['enabled'] ?? null
                );
            }

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $videoChat['platforms_ids'] ?? null
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray(),
                        true
                    );
                }

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Creating vybe appearance case support
                     */
                    $this->vybeAppearanceCaseSupportRepository->store(
                        $appearanceCase,
                        $videoChat['unit_suggestion']
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $realLife['unit_id'] ?? null
            );

            /**
             * Checking the same location existence
             */
            if (isset($realLife['same_location']) && $realLife['same_location'] === true) {

                /**
                 * Getting user current city place
                 */
                $cityPlace = $vybe->user->currentCityPlace;
            } else {

                /**
                 * Getting city place
                 */
                $cityPlace = $this->cityPlaceRepository->findByPlaceId(
                    $realLife['city_place_id'] ?? null
                );
            }

            /**
             * Getting vybe appearance case
             */
            $appearanceCase = $vybe->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getRealLife()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Updating appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->update(
                    $appearanceCase,
                    $vybe,
                    VybeAppearanceList::getRealLife(),
                    $unit,
                    $cityPlace,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    $realLife['same_location'] ?? null,
                    $voiceChat['enabled'] ?? null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    VybeAppearanceList::getRealLife(),
                    $unit,
                    $cityPlace,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    $realLife['same_location'] ?? null,
                    $voiceChat['enabled'] ?? null
                );
            }

            if ($appearanceCase) {

                /**
                 * Checking suggestion existence
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Creating vybe appearance case support
                     */
                    $this->vybeAppearanceCaseSupportRepository->store(
                        $appearanceCase,
                        $realLife['unit_suggestion']
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
                );
            }
        }

        return $responseAppearanceCases;
    }

    /**
     * @param Collection $vybeAppearanceCases
     * @param array $appearanceCases
     *
     * @throws DatabaseException
     */
    public function deleteRemovedAppearanceCases(
        Collection $vybeAppearanceCases,
        array $appearanceCases
    ) : void
    {
        /** @var AppearanceCase $vybeAppearanceCase */
        foreach ($vybeAppearanceCases as $vybeAppearanceCase) {
            if (!isset($appearanceCases[$vybeAppearanceCase->getAppearance()->code])) {
                $this->appearanceCaseRepository->delete(
                    $vybeAppearanceCase
                );
            }
        }
    }

    /**
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function updateAppearanceCasesWithoutSuggestions(
        Vybe $vybe,
        array $appearanceCases
    ) : Collection
    {
        /**
         * Deleting removed appearance cases from an array
         */
        $this->deleteRemovedAppearanceCases(
            $vybe->appearanceCases,
            $appearanceCases
        );

        /**
         * Preparing vybe appearance cases collection
         */
        $responseAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $voiceChat['unit_id'] ?? null
            );

            /**
             * Checking unit existence
             */
            if (!$unit) {

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($voiceChat['unit_suggestion'])) {

                    /**
                     * Getting unit
                     */
                    $unit = $this->unitRepository->findByName(
                        $voiceChat['unit_suggestion']['en']
                    );

                    /**
                     * Checking unit existence
                     */
                    if (!$unit) {

                        /**
                         * Creating unit
                         */
                        $unit = $this->unitRepository->store(
                            $vybe->getType() && $vybe->getType()->isEvent() ?
                                UnitTypeList::getEvent() :
                                UnitTypeList::getUsual(),
                            $voiceChat['unit_suggestion'],
                            null
                        );
                    }
                }
            }

            /**
             * Getting a voice chat appearance case
             */
            $appearanceCase = $vybe->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVoiceChat()->id)
                ->first();

            /**
             * Checking appearance cases existence
             */
            if ($appearanceCase) {

                /**
                 * Updating appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->update(
                    $appearanceCase,
                    $vybe,
                    VybeAppearanceList::getVoiceChat(),
                    $unit,
                    null,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    null,
                    $voiceChat['enabled'] ?? null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    VybeAppearanceList::getVoiceChat(),
                    $unit,
                    null,
                    $voiceChat['price'] ?? null,
                    $voiceChat['description'] ?? null,
                    null,
                    $voiceChat['enabled'] ?? null
                );
            }

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $voiceChat['platforms_ids'] ?? []
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray(),
                        true
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $videoChat['unit_id'] ?? null
            );

            /**
             * Checking unit existence
             */
            if (!$unit) {

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($videoChat['unit_suggestion'])) {

                    /**
                     * Getting unit
                     */
                    $unit = $this->unitRepository->findByName(
                        $videoChat['unit_suggestion']['en']
                    );

                    /**
                     * Checking unit existence
                     */
                    if (!$unit) {

                        /**
                         * Creating unit
                         */
                        $unit = $this->unitRepository->store(
                            $vybe->getType() && $vybe->getType()->isEvent() ?
                                UnitTypeList::getEvent() :
                                UnitTypeList::getUsual(),
                            $videoChat['unit_suggestion'],
                            null
                        );
                    }
                }
            }

            /**
             * Getting a video chat appearance case
             */
            $appearanceCase = $vybe->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVideoChat()->id)
                ->first();

            /**
             * Checking appearance cases existence
             */
            if ($appearanceCase) {

                /**
                 * Updating appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->update(
                    $appearanceCase,
                    $vybe,
                    VybeAppearanceList::getVideoChat(),
                    $unit,
                    null,
                    $videoChat['price'] ?? null,
                    $videoChat['description'] ?? null,
                    null,
                    $videoChat['enabled'] ?? null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    VybeAppearanceList::getVideoChat(),
                    $unit,
                    null,
                    $videoChat['price'] ?? null,
                    $videoChat['description'] ?? null,
                    null,
                    $videoChat['enabled'] ?? null
                );
            }

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Getting platforms
                 */
                $platforms = $this->platformRepository->getByIds(
                    $videoChat['platforms_ids'] ?? null
                );

                /**
                 * Checking platforms existence
                 */
                if ($platforms->count() > 0) {

                    /**
                     * Attaching platforms to an appearance case
                     */
                    $this->appearanceCaseRepository->attachPlatforms(
                        $appearanceCase,
                        $platforms->pluck('id')
                            ->toArray(),
                        true
                    );
                }

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
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

            /**
             * Getting unit
             */
            $unit = $this->unitRepository->findById(
                $realLife['unit_id'] ?? null
            );

            /**
             * Checking unit existence
             */
            if (!$unit) {

                /**
                 * Checking unit suggestion existence
                 */
                if (isset($realLife['unit_suggestion'])) {

                    /**
                     * Getting unit
                     */
                    $unit = $this->unitRepository->findByName(
                        $realLife['unit_suggestion']['en']
                    );

                    /**
                     * Checking unit existence
                     */
                    if (!$unit) {

                        /**
                         * Creating unit
                         */
                        $unit = $this->unitRepository->store(
                            $vybe->getType() && $vybe->getType()->isEvent() ?
                                UnitTypeList::getEvent() :
                                UnitTypeList::getUsual(),
                            $realLife['unit_suggestion'],
                            null
                        );
                    }
                }
            }

            /**
             * Preparing city place variable
             */
            $cityPlace = null;

            /**
             * Checking the same location existence
             */
            if (array_key_exists('same_location', $realLife)) {

                /**
                 * Checking user current city place existence
                 */
                if ($vybe->user->currentCityPlace) {

                    /**
                     * Getting user current city place
                     */
                    $cityPlace = $vybe->user->currentCityPlace;
                }
            } else {

                /**
                 * Getting city place
                 */
                $cityPlace = $this->cityPlaceRepository->findByPlaceId(
                    $realLife['city_place_id'] ?? null
                );
            }

            /**
             * Checking city place existence
             */
            if (!$cityPlace) {

                /**
                 * Creating city place
                 */
                $cityPlace = $this->cityPlaceService->getOrCreate(
                    $realLife['city_place_id']
                );
            }

            /**
             * Getting vybe appearance case
             */
            $appearanceCase = $vybe->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getRealLife()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if ($appearanceCase) {

                /**
                 * Updating appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->update(
                    $appearanceCase,
                    $vybe,
                    VybeAppearanceList::getRealLife(),
                    $unit,
                    $cityPlace,
                    $realLife['price'] ?? null,
                    $realLife['description'] ?? null,
                    $realLife['same_location'] ?? null,
                    $realLife['enabled'] ?? null
                );
            } else {

                /**
                 * Creating vybe appearance case
                 */
                $appearanceCase = $this->appearanceCaseRepository->store(
                    $vybe,
                    VybeAppearanceList::getRealLife(),
                    $unit,
                    $cityPlace,
                    $realLife['price'] ?? null,
                    $realLife['description'] ?? null,
                    array_key_exists('same_location', $realLife) ? $realLife['same_location'] : null,
                    $realLife['enabled'] ?? null
                );
            }

            if ($appearanceCase) {

                /**
                 * Adding an appearance case to response
                 */
                $responseAppearanceCases->push(
                    $appearanceCase
                );
            }
        }

        return $responseAppearanceCases;
    }

    /**
     * @param Vybe $vybe
     * @param array $schedulesItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function createSchedules(
        Vybe $vybe,
        array $schedulesItems
    ) : Collection
    {
        /**
         * Preparing vybe schedules collection
         */
        $schedules = new Collection();

        /** @var array $scheduleItem */
        foreach ($schedulesItems as $scheduleItem) {

            /**
             * Creating vybe schedule
             */
            $schedule = $this->scheduleRepository->store(
                $vybe,
                $scheduleItem['datetime_from'],
                $scheduleItem['datetime_to']
            );

            /**
             * Checking schedule existence
             */
            if ($schedule) {

                /**
                 * Adding vybe schedule to a collection
                 */
                $schedules->add(
                    $schedule
                );
            }
        }

        return $schedules;
    }

    /**
     * @param Vybe $vybe
     * @param array $schedulesItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateSchedules(
        Vybe $vybe,
        array $schedulesItems
    ) : Collection
    {
        /**
         * Deleting vybe schedules
         */
        $this->scheduleRepository->deleteForVybe(
            $vybe
        );

        /**
         * Creating vybe schedules
         */
        return $this->createSchedules(
            $vybe,
            $schedulesItems
        );
    }

    /**
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param int|null $userCount
     *
     * @return VybeTypeListItem|null
     */
    public function getVybeType(
        ?VybePeriodListItem $vybePeriodListItem,
        ?int $userCount
    ) : ?VybeTypeListItem
    {
        if ($vybePeriodListItem) {
            if ($vybePeriodListItem->isOneTime()) {
                return VybeTypeList::getEventItem();
            }

            if ($userCount) {
                if ($userCount > 1) {
                    return VybeTypeList::getGroupItem();
                } elseif ($userCount == 1) {
                    return VybeTypeList::getSoloItem();
                }
            }
        }

        return null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateVybeType(
        Vybe $vybe
    ) : Vybe
    {
        if ($vybe->getPeriod() &&
            $vybe->getPeriod()->isOneTime()
        ) {

            /**
             * Updating vybe type
             */
            $vybe = $this->vybeRepository->updateType(
                $vybe,
                VybeTypeList::getEventItem()
            );
        } else {
            if ($vybe->getPeriod() &&
                !$vybe->getPeriod()->isOneTime() &&
                $vybe->user_count > 1
            ) {

                /**
                 * Updating vybe type
                 */
                $vybe = $this->vybeRepository->updateType(
                    $vybe,
                    VybeTypeList::getGroupItem()
                );
            } else {

                /**
                 * Updating vybe type
                 */
                $vybe = $this->vybeRepository->updateType(
                    $vybe,
                    VybeTypeList::getSoloItem()
                );
            }
        }

        return $vybe;
    }

    /**
     * @param Vybe $vybe
     * @param array|null $files
     *
     * @return bool
     */
    public function checkFilesExistence(
        Vybe $vybe,
        ?array $files
    ) : bool
    {
        /**
         * Counting total files amount
         */
        $filesCount = count($vybe->images_ids ? $vybe->images_ids : []) +
            count($vybe->videos_ids ? $vybe->videos_ids : []);

        /**
         * Checking total files amount
         */
        if ($filesCount == 0) {
            if ((is_array($files) && count($files) == 0) ||
                !$files
            ) {
                return false;
            }
        }

        return true;
    }

    //--------------------------------------------------------------------------
    // Vybe steps

    /**
     * @param Vybe $vybe
     * @param VybeStepListItem $vybeStepListItem
     *
     * @return bool
     */
    public function checkStepForward(
        Vybe $vybe,
        VybeStepListItem $vybeStepListItem
    ) : bool
    {
        if ($vybe->getStep()->id >= $vybeStepListItem->id) {
            return true;
        }

        return ($vybeStepListItem->id - $vybe->getStep()->id) == 1;
    }

    /**
     * @param User $user
     * @param string|null $title
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Collection|null $devices
     * @param string|null $deviceSuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param int|null $userCount
     *
     * @return Vybe|null
     *
     * @throws DatabaseException
     */
    public function storeFirstStep(
        User $user,
        ?string $title,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Collection $devices,
        ?string $deviceSuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?VybePeriodListItem $vybePeriodListItem,
        ?int $userCount
    ) : ?Vybe
    {
        /**
         * Preparing vybe type variable
         */
        $vybeTypeListItem = null;

        /**
         * Checking vybe period and user count existences
         */
        if ($vybePeriodListItem && $userCount) {

            /**
             * Getting vybe type
             */
            $vybeTypeListItem = $this->getVybeTypeByParameters(
                $vybePeriodListItem,
                $userCount
            );
        }

        /**
         * Creating vybe
         */
        $vybe = $this->vybeRepository->store(
            $user,
            $activity,
            $vybeTypeListItem,
            $vybePeriodListItem,
            null,
            null,
            null,
            VybeStatusList::getDraftItem(),
            null,
            null,
            $title,
            $userCount,
            null
        );

        /**
         * Checking vybe existence
         */
        if ($vybe) {

            /**
             * Checking one of the suggestions existence
             */
            if ($category ||
                $categorySuggestion ||
                $subcategory ||
                $subcategorySuggestion ||
                $activitySuggestion ||
                $deviceSuggestion
            ) {

                /**
                 * Creating vybe support
                 */
                $this->vybeSupportRepository->store(
                    $vybe,
                    $category,
                    $categorySuggestion,
                    $subcategory,
                    $subcategorySuggestion,
                    $activitySuggestion,
                    $deviceSuggestion,
                    $activitySuggestion && $devices ?
                        $devices->pluck('id')
                            ->values()
                            ->toArray() : null
                );
            }

            /**
             * Checking activity suggestion existence
             */
            if (!$activitySuggestion) {

                /**
                 * Checking devices existence
                 */
                if ($devices) {

                    /**
                     * Attaching devices to vybe
                     */
                    $this->vybeRepository->attachDevices(
                        $vybe,
                        $devices->pluck('id')
                            ->toArray()
                    );
                }
            }
        }

        return $vybe;
    }

    /**
     * @param Vybe $vybe
     * @param string|null $title
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Collection|null $devices
     * @param string|null $deviceSuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param int|null $userCount
     *
     * @return Vybe|null
     *
     * @throws DatabaseException
     */
    public function updateFirstStep(
        Vybe $vybe,
        ?string $title,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Collection $devices,
        ?string $deviceSuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?VybePeriodListItem $vybePeriodListItem,
        ?int $userCount
    ) : ?Vybe
    {
        /**
         * Preparing vybe type variable
         */
        $vybeTypeListItem = null;

        /**
         * Checking vybe period and user count existences
         */
        if ($vybePeriodListItem && $userCount) {

            /**
             * Getting vybe type
             */
            $vybeTypeListItem = $this->getVybeTypeByParameters(
                $vybePeriodListItem,
                $userCount
            );
        }

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateFirstStep(
            $vybe,
            $activity,
            $vybeTypeListItem,
            $vybePeriodListItem,
            $title,
            $userCount
        );

        /**
         * Checking one of the suggestions existence
         */
        if ($category ||
            $categorySuggestion ||
            $subcategory ||
            $subcategorySuggestion ||
            $activitySuggestion ||
            $deviceSuggestion
        ) {

            /**
             * Checking vybe support existence
             */
            if ($vybe->support) {

                /**
                 * Updating vybe support
                 */
                $this->vybeSupportRepository->update(
                    $vybe->support,
                    $category,
                    $categorySuggestion,
                    $subcategory,
                    $subcategorySuggestion,
                    $activitySuggestion,
                    $deviceSuggestion,
                    $activitySuggestion && $devices ? $devices->pluck('id')
                        ->values()
                        ->toArray() : null
                );
            } else {

                /**
                 * Creating vybe support
                 */
                $this->vybeSupportRepository->store(
                    $vybe,
                    $category,
                    $categorySuggestion,
                    $subcategory,
                    $subcategorySuggestion,
                    $activitySuggestion,
                    $deviceSuggestion,
                    $activitySuggestion && $devices ? $devices->pluck('id')
                        ->values()
                        ->toArray() : null
                );
            }

            /**
             * Checking activity suggestion existence
             */
            if (!$activitySuggestion) {

                /**
                 * Checking devices existence
                 */
                if ($devices) {

                    /**
                     * Attaching devices to vybe
                     */
                    $this->vybeRepository->attachDevices(
                        $vybe,
                        $devices->pluck('id')
                            ->toArray()
                    );
                }
            }
        } else {

            /**
             * Deleting vybe support
             */
            $this->vybeSupportRepository->deleteForVybe(
                $vybe
            );
        }

        return $vybe;
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function checkIfAnyRequestExists(
        Vybe $vybe
    ) : bool
    {
        /**
         * Checking all vybe request existence
         */
        if (($vybe->publishRequest && $vybe->publishRequest->getRequestStatus()->isPending()) ||
            ($vybe->changeRequest && $vybe->changeRequest->getRequestStatus()->isPending()) ||
            ($vybe->unsuspendRequest && $vybe->unsuspendRequest->getRequestStatus()->isPending()) ||
            ($vybe->unpublishRequest && $vybe->unpublishRequest->getRequestStatus()->isPending()) ||
            ($vybe->deletionRequest && $vybe->deletionRequest->getRequestStatus()->isPending())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Collection $orders
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminTypesByOrdersIds(
        Collection $orders
    ) : Collection
    {
        $vybesIds = [];

        /** @var Order $order */
        foreach ($orders as $order) {

            /** @var OrderItem $orderItem */
            foreach ($order->items as $orderItem) {
                $vybeId = $orderItem->vybe_id;

                if (!in_array($vybeId, $vybesIds)) {
                    $vybesIds[] = $vybeId;
                }
            }
        }

        /**
         * Getting vybes types count
         */
        $vybeTypesCounts = $this->vybeRepository->getTypesByIdsCount(
            $vybesIds
        );

        /**
         * Getting vybe statuses
         */
        $vybeTypes = VybeTypeList::getItems();

        /** @var VybeTypeListItem $vybeType */
        foreach ($vybeTypes as $vybeType) {
            if ($vybeType->code == 'group') {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute('grouped')
                );
            } else {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute(
                        $vybeType->code
                    )
                );
            }
        }

        return $vybeTypes;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminTypesByOrderItemsIds(
        Collection $orderItems
    ) : Collection
    {
        $vybesIds = [];

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            $vybeId = $orderItem->vybe_id;

            if (!in_array($vybeId, $vybesIds)) {
                $vybesIds[] = $vybeId;
            }
        }

        /**
         * Getting vybes types count
         */
        $vybeTypesCounts = $this->vybeRepository->getTypesByIdsCount(
            $vybesIds
        );

        /**
         * Getting vybe statuses
         */
        $vybeTypes = VybeTypeList::getItems();

        /** @var VybeTypeListItem $vybeType */
        foreach ($vybeTypes as $vybeType) {

            if ($vybeType->code == 'group') {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute('grouped')
                );
            } else {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute(
                        $vybeType->code
                    )
                );
            }
        }

        return $vybeTypes;
    }

    /**
     * @param Collection $orderInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminTypesByOrderInvoicesIds(
        Collection $orderInvoices
    ) : Collection
    {
        $vybesIds = [];

        /** @var OrderInvoice $orderInvoice */
        foreach ($orderInvoices as $orderInvoice) {

            /** @var OrderItem $orderItem */
            foreach ($orderInvoice->items as $orderItem) {
                $vybeId = $orderItem->vybe_id;

                if (!in_array($vybeId, $vybesIds)) {
                    $vybesIds[] = $vybeId;
                }
            }
        }

        /**
         * Getting vybes types count
         */
        $vybeTypesCounts = $this->vybeRepository->getTypesByIdsCount(
            $vybesIds
        );

        /**
         * Getting vybe statuses
         */
        $vybeTypes = VybeTypeList::getItems();

        /** @var VybeTypeListItem $vybeType */
        foreach ($vybeTypes as $vybeType) {

            if ($vybeType->code == 'group') {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute('grouped')
                );
            } else {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute(
                        $vybeType->code
                    )
                );
            }
        }

        return $vybeTypes;
    }

    /**
     * @param Collection $tips
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminTypesByTipsIds(
        Collection $tips
    ) : Collection
    {
        $vybesIds = [];

        /** @var Tip $tip */
        foreach ($tips as $tip) {
            $vybeId = $tip->item->vybe_id;

            if (!in_array($vybeId, $vybesIds)) {
                $vybesIds[] = $vybeId;
            }
        }

        /**
         * Getting vybes types count
         */
        $vybeTypesCounts = $this->vybeRepository->getTypesByIdsCount(
            $vybesIds
        );

        /**
         * Getting vybe statuses
         */
        $vybeTypes = VybeTypeList::getItems();

        /** @var VybeTypeListItem $vybeType */
        foreach ($vybeTypes as $vybeType) {

            if ($vybeType->code == 'group') {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute('grouped')
                );
            } else {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute(
                        $vybeType->code
                    )
                );
            }
        }

        return $vybeTypes;
    }

    /**
     * @param Collection $sales
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminTypesBySalesIds(
        Collection $sales
    ) : Collection
    {
        $vybesIds = [];

        /** @var Sale $sale */
        foreach ($sales as $sale) {

            /** @var OrderItem $orderItem */
            foreach ($sale->items as $orderItem) {
                $vybeId = $orderItem->vybe_id;

                if (!in_array($vybeId, $vybesIds)) {
                    $vybesIds[] = $vybeId;
                }
            }
        }

        /**
         * Getting vybes types count
         */
        $vybeTypesCounts = $this->vybeRepository->getTypesByIdsCount(
            $vybesIds
        );

        /**
         * Getting vybe statuses
         */
        $vybeTypes = VybeTypeList::getItems();

        /** @var VybeTypeListItem $vybeType */
        foreach ($vybeTypes as $vybeType) {

            if ($vybeType->code == 'group') {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute('grouped')
                );
            } else {

                /**
                 * Setting vybe status count
                 */
                $vybeType->setCount(
                    $vybeTypesCounts->getAttribute(
                        $vybeType->code
                    )
                );
            }
        }

        return $vybeTypes;
    }

    /**
     * @param Category $category
     *
     * @return int
     */
    public function countCategoryVybes(
        Category $category
    ) : int
    {
        $categoryVybesCount = $category->vybes_count;

        /** @var Category $subcategory */
        foreach ($category->subcategories as $subcategory) {
            $categoryVybesCount += $subcategory->vybes_count;
        }

        return $categoryVybesCount;
    }

    /**
     * @param Vybe $vybe
     *
     * @return mixed
     */
    public function getLatestPendingOrDeclinedRequest(
        Vybe $vybe
    ) : mixed
    {
        $requestDates = [
            'publishRequest'   => $vybe->publishRequest ? $vybe->publishRequest->created_at->format('Y-m-d H:i:s') : null,
            'changeRequest'    => $vybe->changeRequest ? $vybe->changeRequest->created_at->format('Y-m-d H:i:s') : null,
            'unpublishRequest' => $vybe->unpublishRequest ? $vybe->unpublishRequest->created_at->format('Y-m-d H:i:s') : null,
            'unsuspendRequest' => $vybe->unsuspendRequest ? $vybe->unsuspendRequest->created_at->format('Y-m-d H:i:s') : null,
            'deletionRequest'  => $vybe->deletionRequest ? $vybe->deletionRequest->created_at->format('Y-m-d H:i:s') : null,
        ];

        if (max($requestDates) !== null) {
            foreach ($requestDates as $key => $requestDate) {
                if ($requestDate == max($requestDates)) {
                    if ($vybe->$key->getRequestStatus()->isPending() ||
                        $vybe->$key->getRequestStatus()->isDeclined()
                    ) {
                        return $vybe->$key;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param Collection $vybes
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesByIds(
        Collection $vybes
    ) : Collection
    {
        /**
         * Getting vybes statuses count
         */
        $vybeStatusesCounts = $this->vybeRepository->getStatusesByIdsCount(
            $vybes->pluck('id')
                ->values()
                ->toArray()
        );

        /**
         * Getting vybe statuses
         */
        $vybeStatuses = VybeStatusList::getItems();

        /** @var VybeStatusListItem $vybeStatus */
        foreach ($vybeStatuses as $vybeStatus) {

            /**
             * Setting vybe status count
             */
            $vybeStatus->setCount(
                $vybeStatusesCounts->getAttribute(
                    $vybeStatus->code
                )
            );
        }

        return $vybeStatuses;
    }

    /**
     * @param Vybe $vybe
     * @param array $settings
     *
     * @throws DatabaseException
     */
    public function updateVybeCustomSettings(
        Vybe $vybe,
        array $settings
    ) : void
    {
        /**
         * Checking handling fees existence
         */
        if (isset($settings['handling_fees'])) {

            /**
             * Setting user
             */
            $this->userHandlingFeesSetting->setUser(
                $vybe->user
            );

            /**
             * Setting vybe
             */
            $this->vybeHandlingFeesSetting->setVybe(
                $vybe
            );

            /**
             * Setting seller handling fee value
             */
            $this->userHandlingFeesSetting->setSellerHandlingFee(
                $settings['handling_fees']['seller_handling_fee']
            );

            /**
             * Setting vybe handling fee value
             */
            $this->vybeHandlingFeesSetting->setVybeSellerHandlingFee(
                $settings['handling_fees']['vybe_handling_fee']
            );

            /**
             * Setting tipping handling fee value
             */
            $this->vybeHandlingFeesSetting->setVybeTippingHandlingFee(
                $settings['handling_fees']['tipping_handling_fee']
            );
        }

        /**
         * Checking the maximum number of users existence
         */
        if (isset($settings['maximum_number_of_users'])) {

            /**
             * Setting user
             */
            $this->maximumNumberOfUsersSetting->setUser(
                $vybe->user
            );

            /**
             * Setting group vybes value
             */
            $this->maximumNumberOfUsersSetting->setGroupVybes(
                $settings['maximum_number_of_users']['group_vybes']
            );

            /**
             * Setting events value
             */
            $this->maximumNumberOfUsersSetting->setEvents(
                $settings['maximum_number_of_users']['events']
            );
        }

        /**
         * Checking the maximum number of users existence
         */
        if (isset($settings['days_that_vybes_can_be_ordered'])) {

            /**
             * Setting user
             */
            $this->daysThatVybesCanBeOrderedSetting->setUser(
                $vybe->user
            );

            /**
             * Setting solo vybes minimum days
             */
            $this->daysThatVybesCanBeOrderedSetting->setSoloVybesMinimumDays(
                $settings['days_that_vybes_can_be_ordered']['solo_vybes.minimum_days']
            );

            /**
             * Setting solo vybes maximum days
             */
            $this->daysThatVybesCanBeOrderedSetting->setSoloVybesMaximumDays(
                $settings['days_that_vybes_can_be_ordered']['solo_vybes.maximum_days']
            );

            /**
             * Setting group vybes minimum days
             */
            $this->daysThatVybesCanBeOrderedSetting->setGroupVybesMinimumDays(
                $settings['days_that_vybes_can_be_ordered']['group_vybes.minimum_days']
            );

            /**
             * Setting group vybes maximum days
             */
            $this->daysThatVybesCanBeOrderedSetting->setGroupVybesMaximumDays(
                $settings['days_that_vybes_can_be_ordered']['group_vybes.maximum_days']
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $accessPassword
     *
     * @return bool
     */
    public function checkAccessPassword(
        Vybe $vybe,
        string $accessPassword
    ) : bool
    {
        return strcmp($accessPassword, Crypt::decrypt($vybe->access_password)) == 0;
    }

    /**
     * @param VybeTypeListItem $vybeTypeListItem
     * @param int $userCount
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function isUserCountNotAllowed(
        VybeTypeListItem $vybeTypeListItem,
        int $userCount
    ) : bool
    {
        if ($vybeTypeListItem->isGroup()) {
            return $userCount > $this->maximumNumberOfUsersSetting->getGroupVybes();
        }

        if ($vybeTypeListItem->isEvent()) {
            return $userCount > $this->maximumNumberOfUsersSetting->getEvents();
        }

        return false;
    }

    /**
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     */
    public function checkUserAgeLimit(
        Vybe $vybe,
        User $user
    ) : bool
    {
        /**
         * Checking age limit existence
         */
        if ($vybe->getAgeLimit()) {

            /**
             * Checking vybe age limit 16+
             */
            if ($vybe->getAgeLimit()->isSixteenPlus() &&
                Carbon::now()->diffInYears($user->birth_date) < 16
            ) {
                return false;
            }

            /**
             * Checking vybe age limit 18+
             */
            if ($vybe->getAgeLimit()->isEighteenPlus() &&
                Carbon::now()->diffInYears($user->birth_date) < 18
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Collection $categories
     *
     * @return Collection
     */
    public function getByCategories(
        Collection $categories
    ) : Collection
    {
        $vybes = new Collection();

        /** @var Category $category */
        foreach ($categories as $category) {
            if ($category->relationLoaded('vybes')) {
                foreach ($category->vybes as $vybe) {
                    $vybes->push(
                        $vybe
                    );
                }
            }

            if ($category->relationLoaded('subcategories')) {

                /** @var Category $subcategory */
                foreach ($category->subcategories as $subcategory) {
                    if ($subcategory->relationLoaded('vybes')) {
                        foreach ($subcategory->vybes as $vybe) {
                            $vybes->push(
                                $vybe
                            );
                        }
                    }
                }
            }
        }

        return $vybes;
    }

    /**
     * @param Category $category
     *
     * @return int
     */
    public function getCountByCategory(
        Category $category
    ) : int
    {
        $vybesCount = $category->vybes_count;

        if ($category->relationLoaded('subcategories')) {

            /** @var Category $subcategory */
            foreach ($category->subcategories as $subcategory) {
                $vybesCount += $subcategory->vybes_count;
            }
        }

        return $vybesCount;
    }

    /**
     * @param Activity $activity
     *
     * @return Collection
     */
    public function getByActivity(
        Activity $activity
    ) : Collection
    {
        $vybes = new Collection();

        if ($activity->relationLoaded('vybes')) {
            foreach ($activity->vybes as $vybe) {
                $vybes->push(
                    $vybe
                );
            }
        }

        return $vybes;
    }

    /**
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByUsers(
        Collection $users
    ) : Collection
    {
        $vybes = new Collection();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->relationLoaded('vybes')) {
                foreach ($user->vybes as $vybe) {
                    $vybes->push(
                        $vybe
                    );
                }
            }
        }

        return $vybes;
    }

    /**
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByFullProfile(
        Collection $users
    ) : Collection
    {
        $vybes = new Collection();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->relationLoaded('vybes')) {

                /** @var Vybe $vybe */
                foreach ($user->vybes as $vybe) {
                    $vybes->push(
                        $vybe
                    );
                }
            }

            if ($user->relationLoaded('favoriteVybes')) {

                /** @var Vybe $vybe */
                foreach ($user->favoriteVybes as $vybe) {
                    $vybes->push(
                        $vybe
                    );
                }
            }
        }

        return $vybes;
    }

    /**
     * @param Vybe $vybe
     * @param Carbon $startDate
     * @param int|null $offset
     *
     * @return array
     */
    public function getScheduledCalendar(
        Vybe $vybe,
        Carbon $startDate,
        ?int $offset = 0
    ) : array
    {
        /**
         * Preparing calendar variable
         */
        $calendar = [];

        /**
         * Adding offset to start date
         */
        $startDate = Carbon::parse($startDate)
            ->addSeconds($offset);

        /**
         * Prepare schedules collection
         */
        $schedules = new Collection();

        /** @var array $schedule */
        foreach ($vybe->schedules as $schedule) {

            /**
             * Applying timezone offset to schedule
             */
            $schedule->datetime_from = Carbon::parse($schedule->datetime_from)->addSeconds($offset);
            $schedule->datetime_to = Carbon::parse($schedule->datetime_to)->addSeconds($offset);

            /**
             * Add schedules to a collection
             */
            $schedules->push(
                $schedule
            );
        }

        if ($vybe->getType()->isEvent()) {

            /**
             * Creating datetime
             */
            $datetime = Carbon::create(
                $schedule->datetime_from->year,
                $schedule->datetime_from->month,
                $schedule->datetime_from->day
            )->addSeconds($offset);

            $calendar[] = [
                'datetime'  => $datetime->format('Y-m-d\TH:i:s.v\Z'),
                'weekDay'   => $datetime->englishDayOfWeek,
                'schedules' => [
                    [
                        'datetime_from' => Carbon::parse($schedule->datetime_from)
                            ->format('Y-m-d\TH:i:s.v\Z'),
                        'datetime_to'   => Carbon::parse($schedule->datetime_to)
                            ->format('Y-m-d\TH:i:s.v\Z')
                    ]
                ]
            ];
        } else {

            /**
             * Iterate calendar days to vybe order advance
             */
            for ($i = 0; $i < $vybe->order_advance; $i++) {

                /**
                 * Preparing day schedules variables
                 */
                $daySchedules = [];

                /** @var Schedule $vybeSchedule */
                foreach ($schedules as $vybeSchedule) {

                    /**
                     * Checking day of the week
                     */
                    if ($vybeSchedule->datetime_from->dayOfWeekIso == $startDate->dayOfWeekIso) {

                        /**
                         * Creating datetime from
                         */
                        $datetimeFrom = Carbon::create(
                            $startDate->year,
                            $startDate->month,
                            $startDate->day,
                            $vybeSchedule->datetime_from->hour,
                            $vybeSchedule->datetime_from->minute,
                            $vybeSchedule->datetime_from->second
                        );

                        /**
                         * Creating datetime to
                         */
                        $datetimeTo = Carbon::create(
                            $startDate->year,
                            $startDate->month,
                            $startDate->day,
                            $vybeSchedule->datetime_to->hour,
                            $vybeSchedule->datetime_to->minute,
                            $vybeSchedule->datetime_to->second
                        );

                        /**
                         * Checking schedule datetime to is the next day
                         */
                        if ($datetimeTo->dayOfWeekIso < $vybeSchedule->datetime_to->dayOfWeekIso) {
                            $datetimeTo = $datetimeTo->addDay();
                        }

                        /**
                         * Adding day schedule
                         */
                        $daySchedules[] = [
                            'datetime_from' => $datetimeFrom->format('Y-m-d\TH:i:s.v\Z'),
                            'datetime_to'   => $datetimeTo->format('Y-m-d\TH:i:s.v\Z')
                        ];
                    }
                }

                /**
                 * Creating datetime
                 */
                $datetime = Carbon::create(
                    $startDate->year,
                    $startDate->month,
                    $startDate->day
                );

                /**
                 * Adding calendar day
                 */
                $calendar[] = [
                    'datetime'  => $datetime->format('Y-m-d\TH:i:s.v\Z'),
                    'weekDay'   => $datetime->englishDayOfWeek,
                    'schedules' => $daySchedules
                ];

                /**
                 * Incrementing day
                 */
                $startDate->addDay();
            }
        }

        return $calendar;
    }

    /**
     * @param Vybe $vybe
     * @param Carbon $startDate
     * @param int|null $offset
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function getSoloCalendarForOrder(
        Vybe $vybe,
        Carbon $startDate,
        ?int $offset = 0
    ) : array
    {
        /**
         * Getting first date
         */
        $firstDate = Carbon::parse($startDate);

        /**
         * Getting last date
         */
        $lastDate = Carbon::parse($startDate)->addDays(
            $vybe->order_advance
        );

        /**
         * Getting datetime range timeslots
         */
        $timeslots = $this->timeslotService->getForCalendar(
            $vybe,
            $firstDate,
            $lastDate,
            $offset
        );

        /**
         * Getting vybe scheduled calendar
         */
        $calendar = $this->getScheduledCalendar(
            $vybe,
            $firstDate,
            $offset
        );

        /**
         * @var int $dayKey
         * @var array $day
         */
        foreach ($calendar as $dayKey => $day) {

            /**
             * Preparing parsed schedules variable
             */
            $parsedSchedules = [];

            /** @var array $schedule */
            foreach ($day['schedules'] as $schedule) {

                /**
                 * Getting schedule range timeslots
                 */
                $dateTimeslots = $timeslots->filter(function ($item) use ($schedule) {
                    return $item->datetime_from >= Carbon::parse($schedule['datetime_from']) &&
                        $item->datetime_to <= Carbon::parse($schedule['datetime_to']);
                })->sortBy('datetime_from');

                /**
                 * Checking timeslot existence
                 */
                if ($dateTimeslots->count()) {

                    /**
                     * Preparing free schedules variable
                     */
                    $freeSchedules = [];

                    /**
                     * Checking is one timeslot
                     */
                    if (count($dateTimeslots) == 1) {
                        $firstKey = array_key_first($dateTimeslots->toArray());

                        if ($dateTimeslots[$firstKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z') > $schedule['datetime_from']) {
                            $freeSchedules[] = [
                                'datetime_from' => $schedule['datetime_from'],
                                'datetime_to'   => $dateTimeslots[$firstKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z')
                            ];

                            if ($dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $schedule['datetime_to']) {
                                $freeSchedules[] = [
                                    'datetime_from' => $dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                    'datetime_to'   => $schedule['datetime_to']
                                ];
                            }
                        } elseif ($dateTimeslots[$firstKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z') == $schedule['datetime_from']) {
                            if ($dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $schedule['datetime_to']) {
                                $freeSchedules[] = [
                                    'datetime_from' => $dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                    'datetime_to'   => $schedule['datetime_to']
                                ];
                            }
                        }
                    }

                    /**
                     * Checking is two timeslots
                     */
                    if (count($dateTimeslots) == 2) {
                        $firstKey = array_key_first($dateTimeslots->toArray());
                        $lastKey = array_key_last($dateTimeslots->toArray());

                        if ($dateTimeslots[$firstKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z') > $schedule['datetime_from']) {
                            $freeSchedules[] = [
                                'datetime_from' => $schedule['datetime_from'],
                                'datetime_to'   => $dateTimeslots[$firstKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z')
                            ];

                            if ($dateTimeslots[$lastKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z') > $dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z')) {
                                $freeSchedules[] = [
                                    'datetime_from' => $dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                    'datetime_to'   => $dateTimeslots[$lastKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z')
                                ];
                            }

                            if ($dateTimeslots[$lastKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $schedule['datetime_to']) {
                                $freeSchedules[] = [
                                    'datetime_from' => $dateTimeslots[$lastKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                    'datetime_to'   => $schedule['datetime_to']
                                ];
                            }
                        } elseif ($dateTimeslots[$firstKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z') == $schedule['datetime_from']) {
                            if ($dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $dateTimeslots[$lastKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z')) {
                                $freeSchedules[] = [
                                    'datetime_from' => $dateTimeslots[$firstKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                    'datetime_to'   => $dateTimeslots[$lastKey]->datetime_from->format('Y-m-d\TH:i:s.v\Z')
                                ];

                                if ($dateTimeslots[$lastKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $schedule['datetime_to']) {
                                    $freeSchedules[] = [
                                        'datetime_from' => $dateTimeslots[$lastKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                        'datetime_to'   => $schedule['datetime_to']
                                    ];
                                }
                            } elseif ($dateTimeslots[$lastKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $schedule['datetime_to']) {
                                $freeSchedules[] = [
                                    'datetime_from' => $dateTimeslots[$lastKey]->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                    'datetime_to'   => $schedule['datetime_to']
                                ];
                            }
                        }
                    }

                    /**
                     * Checking is more than two timeslots
                     */
                    if (count($dateTimeslots) > 2) {
                        $firstKey = array_key_first($dateTimeslots->toArray());
                        $lastKey = array_key_last($dateTimeslots->toArray());

                        foreach ($dateTimeslots as $i => $dateTimeslot) {
                            if ($i == $firstKey) {
                                if ($dateTimeslot->datetime_from->format('Y-m-d\TH:i:s.v\Z') > $schedule['datetime_from']) {
                                    $freeSchedules[] = [
                                        'datetime_from' => $schedule['datetime_from'],
                                        'datetime_to'   => $dateTimeslot->datetime_from->format('Y-m-d\TH:i:s.v\Z')
                                    ];
                                }
                            }

                            if ($i <= $lastKey) {
                                if (isset($dateTimeslots[$i + 1])) {
                                    if ($dateTimeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $dateTimeslots[$i + 1]->datetime_from->format('Y-m-d\TH:i:s.v\Z')) {
                                        $freeSchedules[] = [
                                            'datetime_from' => $dateTimeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                            'datetime_to'   => $dateTimeslots[$i + 1]->datetime_from->format('Y-m-d\TH:i:s.v\Z')
                                        ];
                                    }
                                } else {
                                    if ($dateTimeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z') < $schedule['datetime_to']) {
                                        $freeSchedules[] = [
                                            'datetime_from' => $dateTimeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                                            'datetime_to'   => $schedule['datetime_to']
                                        ];
                                    }
                                }
                            }
                        }
                    }

                    /** @var array $freeSchedule */
                    foreach ($freeSchedules as $freeSchedule) {

                        /**
                         * Adding free schedule to parsed schedules
                         */
                        $parsedSchedules[] = $freeSchedule;
                    }
                } else {

                    /**
                     * Adding schedule to parsed schedules
                     */
                    $parsedSchedules[] = $schedule;
                }
            }

            /**
             * Add parsed schedules to calendar day
             */
            $calendar[$dayKey]['schedules'] = $parsedSchedules;
        }

        return $this->completeCalendarDays(
            $calendar
        );
    }

    /**
     * @param Vybe $vybe
     * @param Carbon $startDate
     * @param User|null $authUser
     * @param int|null $offset
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function getGroupCalendarForOrder(
        Vybe $vybe,
        Carbon $startDate,
        ?User $authUser = null,
        ?int $offset = 0
    ) : array
    {
        /**
         * Getting first date
         */
        $firstDate = Carbon::parse($startDate);

        /**
         * Getting last date
         */
        $lastDate = Carbon::parse($startDate)->addDays(
            $vybe->order_advance
        );

        /**
         * Getting datetime range timeslots
         */
        $timeslots = $this->timeslotService->getForCalendar(
            $vybe,
            $firstDate,
            $lastDate,
            $offset
        );

        /**
         * Getting vybe scheduled calendar
         */
        $calendar = $this->getScheduledCalendar(
            $vybe,
            $firstDate,
            $offset
        );

        /**
         * @var int $dayKey
         * @var array $day
         */
        foreach ($calendar as $dayKey => $day) {

            /**
             * @var int $scheduleKey
             * @var array $schedule
             */
            foreach ($day['schedules'] as $scheduleKey => $schedule) {

                /** @var Timeslot $dateTimeslot */
                $dateTimeslot = $timeslots->filter(function ($item) use ($schedule) {
                    return $item->datetime_from >= Carbon::parse($schedule['datetime_from']) &&
                        $item->datetime_to <= Carbon::parse($schedule['datetime_to']);
                })->first();

                $scheduled = false;
                $hasVacant = true;

                if ($dateTimeslot) {
                    if ($authUser) {
                        $scheduled = $dateTimeslot->users
                            ->contains('id', $authUser->id);
                    }

                    $hasVacant = $dateTimeslot->orderItems
                            ->where('vybe_id', $vybe->id)
                            ->first()
                            ->user_count >= $dateTimeslot->users_count;
                }

                $calendar[$dayKey]['schedules'][$scheduleKey]['scheduled'] = $scheduled;
                $calendar[$dayKey]['schedules'][$scheduleKey]['has_vacant'] = $hasVacant;

                if ($dateTimeslot) {

                    $users = [];

                    /** @var User $user */
                    foreach ($dateTimeslot->users as $user) {
                        $users[] = [
                            'id'           => $user->id,
                            'auth_id'      => $user->auth_id,
                            'avatar_id'    => $user->avatar_id,
                            'username'     => $user->username,
                            'label'        => $user->getLabel(),
                            'state_status' => $user->getStateStatus(),
                            'vybes_count'  => $user->vybes_count,
                            'is_followed'  => $authUser && $user->subscribers->contains('pivot.user_id', $authUser->id),
                            'is_follower'  => $authUser && $authUser->subscriptions->contains('pivot.subscription_id', $authUser->id)
                        ];
                    }

                    $calendar[$dayKey]['schedules'][$scheduleKey]['timeslots'][] = [
                        'id'            => $dateTimeslot->id,
                        'datetime_from' => $dateTimeslot->datetime_from->format('Y-m-d\TH:i:s.v\Z'),
                        'datetime_to'   => $dateTimeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z'),
                        'users_count'   => $dateTimeslot->users_count,
                        'users'         => $users
                    ];
                }
            }
        }

        return $this->completeCalendarDays(
            $calendar,
            true
        );
    }

    /**
     * @param Vybe $vybe
     * @param User|null $authUser
     * @param int|null $offset
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function getEventCalendarForOrder(
        Vybe $vybe,
        ?User $authUser = null,
        ?int $offset = 0
    ) : array
    {
        $calendar = [];

        /** @var Schedule $schedule */
        foreach ($vybe->schedules as $schedule) {

            /**
             * Getting datetime range timeslots
             */
            $timeslots = $this->timeslotService->getForCalendar(
                $vybe,
                $schedule->datetime_from,
                $schedule->datetime_to,
                $offset
            );

            /**
             * Getting vybe scheduled calendar
             */
            $calendar = $this->getScheduledCalendar(
                $vybe,
                $schedule->datetime_from,
                $offset
            );

            /**
             * Getting schedule range timeslots
             */
            $dateTimeslot = $timeslots->filter(function ($item) use ($schedule) {
                return $item->datetime_from >= $schedule->datetime_from &&
                    $item->datetime_to <= $schedule->datetime_to;
            })->first();

            /**
             * Checking timeslot existence
             */
            if ($dateTimeslot) {

                /**
                 * Preparing users variable
                 */
                $users = [];

                /** @var User $user */
                foreach ($dateTimeslot->users as $user) {
                    $users[] = [
                        'id'           => $user->id,
                        'auth_id'      => $user->auth_id,
                        'avatar_id'    => $user->avatar_id,
                        'username'     => $user->username,
                        'label'        => $user->getLabel(),
                        'state_status' => $user->getStateStatus(),
                        'vybes_count'  => $user->vybes_count,
                        'is_followed'  => $authUser && $user->subscribers->contains('pivot.user_id', $authUser->id),
                        'is_follower'  => $authUser && $authUser->subscriptions->contains('pivot.subscription_id', $authUser->id)
                    ];
                }

                $calendar[0]['schedules'][0]['timeslots'][] = [
                    'id'            => $dateTimeslot->id,
                    'datetime_from' => Carbon::parse($dateTimeslot->datetime_from)
                        ->format('Y-m-d\TH:i:s.v\Z'),
                    'datetime_to'   => Carbon::parse($dateTimeslot->datetime_to)
                        ->format('Y-m-d\TH:i:s.v\Z'),
                    'users_count'   => $dateTimeslot->users_count,
                    'users'         => $users
                ];
            }
        }

        return $calendar;
    }

    /**
     * @param array $calendar
     * @param bool $isMonth
     *
     * @return array
     */
    public function completeCalendarDays(
        array $calendar,
        bool $isMonth = false
    ) : array
    {
        /**
         * Getting first month points
         */
        $firstCalendarDay = Carbon::parse($calendar[0]['datetime']);

        $firstMonthDay = $isMonth ?
            Carbon::parse($calendar[0]['datetime'])->firstOfMonth() :
            Carbon::parse($calendar[0]['datetime'])->startOfWeek();

        /**
         * Getting last month points
         */
        $lastCalendarDay = Carbon::parse($calendar[count($calendar) - 1]['datetime']);
        $lastMonthDay = $isMonth ?
            Carbon::parse($calendar[count($calendar) - 1]['datetime'])->lastOfMonth() :
            Carbon::parse($calendar[count($calendar) - 1]['datetime'])->endOfWeek();

        /**
         * Checking the beginning of the month
         */
        if (!$firstCalendarDay->startOfDay()
            ->equalTo($firstMonthDay->startOfDay())
        ) {

            /**
             * Getting a first part period
             */
            $period = CarbonPeriod::create(
                $firstMonthDay->startOfDay(),
                $firstCalendarDay->startOfDay()
            );

            /**
             * Preparing days variable
             */
            $days = [];

            /** @var Carbon $day */
            foreach ($period as $day) {

                /**
                 * Checking the moth day
                 */
                if (!$day->startOfDay()->equalTo($firstCalendarDay->startOfDay())) {
                    $days[] = [
                        'datetime'  => $day->format('Y-m-d\TH:i:s.v\Z'),
                        'weekDay'   => $day->englishDayOfWeek,
                        'schedules' => [],
                        'took_part' => true
                    ];
                }
            }

            /**
             * Merging the first part of a calendar
             */
            $calendar = array_merge($days, $calendar);
        }

        /**
         * Checking the end of the month
         */
        if (!$lastCalendarDay->startOfDay()
            ->equalTo($lastMonthDay->startOfDay())
        ) {

            /**
             * Getting a last part period
             */
            $period = CarbonPeriod::create(
                $lastCalendarDay->startOfDay(),
                $lastMonthDay->startOfDay()
            );

            /**
             * Preparing days variable
             */
            $days = [];

            /** @var Carbon $day */
            foreach ($period as $day) {

                /**
                 * Checking the moth day
                 */
                if (!$day->startOfDay()->equalTo($lastCalendarDay->startOfDay())) {
                    $days[] = [
                        'datetime'  => $day->format('Y-m-d\TH:i:s.v\Z'),
                        'weekDay'   => $day->englishDayOfWeek,
                        'schedules' => []
                    ];
                }
            }

            /**
             * Merging the last part of a calendar
             */
            $calendar = array_merge($calendar, $days);
        }

        return $calendar;
    }

    /**
     * @param User $user
     * @param VybeTypeListItem $vybeTypeListItem
     *
     * @return void
     */
    public function sendToAllFollowers(
        User $user,
        VybeTypeListItem $vybeTypeListItem
    ) : void
    {
        /** @var User $subscriber */
        foreach ($user->subscribers as $subscriber) {

            /**
             * Checking vybe type
             */
            if (!$vybeTypeListItem->isEvent()) {

                /**
                 * Sending vybe available email notification
                 */
                $this->emailNotificationService->sendFollowerVybeAvailable(
                    $user,
                    $subscriber
                );
            } else {

                /**
                 * Sending event available email notification
                 */
                $this->emailNotificationService->sendFollowerEventAvailable(
                    $user,
                    $subscriber
                );
            }
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @throws DatabaseException
     */
    public function delete(
        Vybe $vybe
    ) : void
    {
        /**
         * Deleting appearance cases
         */
        $this->appearanceCaseRepository->deleteForceForVybe(
            $vybe
        );

        /**
         * Deleting schedules
         */
        $this->scheduleRepository->deleteForceForVybe(
            $vybe
        );

        /**
         * Deleting vybe
         */
        $this->vybeRepository->forceDelete(
            $vybe
        );
    }
}
