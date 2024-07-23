<?php

namespace App\Services\Vybe;

use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Microservices\Media\Responses\VybeImageResponse;
use App\Microservices\Media\Responses\VybeVideoResponse;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Schedule;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\Vybe\VybePublishRequestAppearanceCaseRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybePublishRequestScheduleRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Suggestion\CsauSuggestionService;
use App\Services\Vybe\Interfaces\VybePublishServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybePublishRequestService
 *
 * @package App\Services\Vybe
 */
class VybePublishRequestService implements VybePublishServiceInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

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
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybePublishRequestAppearanceCaseRepository
     */
    protected VybePublishRequestAppearanceCaseRepository $vybePublishRequestAppearanceCaseRepository;

    /**
     * @var VybePublishRequestScheduleRepository
     */
    protected VybePublishRequestScheduleRepository $vybePublishRequestScheduleRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * VybePublishRequestService constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var CsauSuggestionService csauSuggestionService */
        $this->csauSuggestionService = new CsauSuggestionService();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybePublishRequestAppearanceCaseRepository vybePublishRequestAppearanceCaseRepository */
        $this->vybePublishRequestAppearanceCaseRepository = new VybePublishRequestAppearanceCaseRepository();

        /** @var VybePublishRequestScheduleRepository vybePublishRequestScheduleRepository */
        $this->vybePublishRequestScheduleRepository = new VybePublishRequestScheduleRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function executePublishRequestForVybe(
        Vybe $vybe
    ) : ?VybePublishRequest
    {
        /**
         * Checking support activity suggestion existence
         */
        if ($vybe->support->activity_suggestion) {

            /**
             * Getting device ids
             */
            $devicesIds = $vybe->support
                ->devices_ids;
        } else {

            /**
             * Getting device ids
             */
            $devicesIds = $vybe->devices ?
                $vybe->devices
                    ->pluck('id')
                    ->toArray() : null;
        }

        /**
         * Getting category
         */
        $category = $vybe->category ? $vybe->category :
            ($vybe->support->category ? $vybe->support->category : null);

        /**
         * Getting subcategory
         */
        $subcategory = $vybe->subcategory ? $vybe->subcategory :
            ($vybe->support->subcategory ? $vybe->support->subcategory : null);

        /**
         * Creating vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->store(
            $vybe,
            $vybe->title,
            $category,
            $vybe->category_suggestion,
            $subcategory,
            $vybe->subcategory_suggestion,
            $vybe->activity,
            $vybe->activity_suggestion,
            $devicesIds,
            $vybe->device_suggestion,
            $vybe->getPeriod(),
            $vybe->user_count,
            $vybe->getType(),
            $vybe->order_advance,
            $vybe->images_ids,
            $vybe->videos_ids,
            $vybe->access_password,
            $vybe->getAccess(),
            $vybe->getShowcase(),
            VybeOrderAcceptList::getAuto(),
            VybeStatusList::getPublishedItem()
        );

        /**
         * Checking vybe publish request existence
         */
        if (!$vybePublishRequest) {
            throw new ValidationException(
                trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.create'),
                'vybe.publishRequest'
            );
        }

        /**
         * Updating vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->updateLanguage(
            $vybePublishRequest,
            $vybe->user->getLanguage()
        );

        /** @var AppearanceCase $appearanceCase */
        foreach ($vybe->appearanceCases as $appearanceCase) {

            /**
             * Getting platforms ids
             */
            $platformsIds = $appearanceCase->platforms ?
                $appearanceCase->platforms->pluck('id')->toArray() :
                null;

            /**
             * Creating vybe publish request appearance case
             */
            $this->vybePublishRequestAppearanceCaseRepository->store(
                $vybePublishRequest,
                $appearanceCase->getAppearance(),
                $appearanceCase->price,
                $appearanceCase->unit,
                $appearanceCase->unit_suggestion,
                $appearanceCase->description,
                $platformsIds,
                $appearanceCase->same_location,
                $appearanceCase->cityPlace,
                $appearanceCase->enabled
            );
        }

        /** @var Schedule $schedule */
        foreach ($vybe->schedules as $schedule) {

            /**
             * Creating vybe publish request schedule
             */
            $this->vybePublishRequestScheduleRepository->store(
                $vybePublishRequest,
                $schedule->datetime_from,
                $schedule->datetime_to
            );
        }

        /**
         * Executing CSAU suggestions to vybe publish request
         */
        $this->csauSuggestionService->executeForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Checking vybe public request device suggestion existence
         */
        if (!is_null($vybePublishRequest->device_suggestion)) {

            /**
             * Creating a device suggestion
             */
            $deviceSuggestion = $this->deviceSuggestionRepository->store(
                $vybePublishRequest,
                null,
                null,
                $vybePublishRequest->device_suggestion
            );

            /**
             * Checking a device suggestion created
             */
            if ($deviceSuggestion) {

                /**
                 * Update vybe publish request device suggestion
                 */
                $vybePublishRequest = $this->vybePublishRequestRepository->updateDeviceSuggestion(
                    $vybePublishRequest,
                    $deviceSuggestion
                );
            }
        }

        /**
         * Releasing vybe publish request counter-caches
         */
        $this->adminNavbarService->releaseVybePublishRequestCache();

        return $vybePublishRequest;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return VybePublishRequest
     *
     * @throws DatabaseException
     */
    public function updateByCsauSuggestions(
        VybePublishRequest $vybePublishRequest
    ) : VybePublishRequest
    {
        /**
         * Getting vybe publish request CSAU suggestions
         */
        $csauSuggestions = $this->csauSuggestionRepository->getAllForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Checking CSAU suggestions exist
         */
        if ($csauSuggestions->count()) {

            /**
             * Checking vybe publish request category suggested
             */
            if (!$vybePublishRequest->category) {

                /**
                 * Checking is CSAU suggestion category accepted
                 */
                if ($csauSuggestions->first()->category) {

                    /**
                     * Update vybe publish request category
                     */
                    $this->vybePublishRequestRepository->updateSuggestedCategory(
                        $vybePublishRequest,
                        $csauSuggestions->first()->category
                    );
                }
            }

            /**
             * Checking vybe publish request subcategory suggested
             */
            if (!$vybePublishRequest->subcategory) {

                /**
                 * Checking is CSAU suggestion subcategory accepted
                 */
                if ($csauSuggestions->first()->subcategory) {

                    /**
                     * Updating vybe publish request subcategory
                     */
                    $this->vybePublishRequestRepository->updateSuggestedSubcategory(
                        $vybePublishRequest,
                        $csauSuggestions->first()->subcategory
                    );
                }
            }

            /**
             * Checking vybe publish request activity suggested
             */
            if (!$vybePublishRequest->activity) {

                /**
                 * Checking is CSAU suggestion activity accepted
                 */
                if ($csauSuggestions->first()->activity) {

                    /**
                     * Updating vybe publish request activity
                     */
                    $this->vybePublishRequestRepository->updateSuggestedActivity(
                        $vybePublishRequest,
                        $csauSuggestions->first()->activity
                    );

                    /**
                     * Update vybe
                     */
                    $this->vybeRepository->updateActivity(
                        $vybePublishRequest->vybe,
                        $csauSuggestions->first()->activity
                    );
                }
            }

            /** @var VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase */
            foreach ($vybePublishRequest->appearanceCases as $vybePublishRequestAppearanceCase) {

                $csauSuggestion = $this->csauSuggestionRepository->findFullById(
                    $vybePublishRequestAppearanceCase->csau_suggestion_id
                );

                if ($csauSuggestion) {

                    /**
                     * Updating vybe publish request appearance case
                     */
                    $this->vybePublishRequestAppearanceCaseRepository->updateUnit(
                        $vybePublishRequestAppearanceCase,
                        $csauSuggestion->unit,
                        $csauSuggestion->unit_suggestion
                    );

                    /**
                     * Update vybe appearance case unit
                     */
                    $this->appearanceCaseRepository->updateUnitForVybeByAppearance(
                        $vybePublishRequest->vybe,
                        $vybePublishRequestAppearanceCase->getAppearance(),
                        $csauSuggestion->unit
                    );
                }
            }
        }

        return $vybePublishRequest;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybePublishRequest
     *
     * @throws DatabaseException
     */
    public function updateByDeviceSuggestion(
        VybePublishRequest $vybePublishRequest,
        DeviceSuggestion $deviceSuggestion
    ) : VybePublishRequest
    {
        /**
         * Checking device suggestion existence
         */
        if ($deviceSuggestion->device) {

            /**
             * Updating device suggestion
             */
            $this->vybePublishRequestRepository->updateSuggestedDevice(
                $vybePublishRequest,
                $deviceSuggestion->device
            );
        }

        return $vybePublishRequest;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param array $appearanceCasesStatuses
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateAppearanceCasesStatuses(
        VybePublishRequest $vybePublishRequest,
        array $appearanceCasesStatuses
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

            /** @var VybePublishRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybePublishRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVoiceChat()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if (!$appearanceCase) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.voiceChat'),
                    'appearance_cases.voice_chat.absence'
                );
            }

            /**
             * Getting CSAU suggestion
             */
            $csauSuggestion = $appearanceCase->csauSuggestion;

            /**
             * Getting price status
             */
            $priceStatus = RequestFieldStatusList::getItem(
                $voiceChat['price_status_id']
            );

            /**
             * Checking price status existence
             */
            if (!$priceStatus) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.priceStatus'),
                    'appearance_cases.voice_chat.price_status_id'
                );
            }

            /**
             * Getting unit status
             */
            $unitStatus = RequestFieldStatusList::getItem(
                $voiceChat['unit_status_id']
            );

            /**
             * Checking unit status existence
             */
            if (!$unitStatus) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.unitStatus'),
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
                    if ($csauSuggestion->getUnitStatus()->isPending() &&
                        $unitStatus->isAccepted()
                    ) {
                        throw new ValidationException(
                            trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.csau.unit.pending'),
                            'appearance_cases.voice_chat.unit_status_id'
                        );
                    }
                }
            }

            /**
             * Getting description status
             */
            $descriptionStatus = RequestFieldStatusList::getItem(
                $voiceChat['description_status_id'] ?? null
            );

            /**
             * Checking description status existence
             */
            if ($descriptionStatus) {

                /**
                 * Checking description existence
                 */
                if (!$appearanceCase->description) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.descriptionStatus'),
                        'appearance_cases.voice_chat.description_status_id'
                    );
                }
            }

            /**
             * Getting platform status
             */
            $platformStatus = RequestFieldStatusList::getItem(
                $voiceChat['platform_status_id'] ?? null
            );

            /**
             * Checking platform status existence
             */
            if ($platformStatus) {

                /**
                 * Checking platforms existence
                 */
                if (!$appearanceCase->platforms_ids) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.platformsStatus'),
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

            /** @var VybePublishRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybePublishRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getVideoChat()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if (!$appearanceCase) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.videoChat'),
                    'appearance_cases.video_chat.absence'
                );
            }

            /**
             * Getting CSAU suggestion
             */
            $csauSuggestion = $appearanceCase->csauSuggestion;

            /**
             * Getting price status
             */
            $priceStatus = RequestFieldStatusList::getItem(
                $videoChat['price_status_id']
            );

            /**
             * Checking price status existence
             */
            if (!$priceStatus) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.priceStatus'),
                    'appearance_cases.video_chat.price_status_id'
                );
            }

            /**
             * Getting unit status
             */
            $unitStatus = RequestFieldStatusList::getItem(
                $videoChat['unit_status_id']
            );

            /**
             * Checking unit status existence
             */
            if (!$unitStatus) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.unitStatus'),
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
                    if ($csauSuggestion->getUnitStatus()->isPending() &&
                        $unitStatus->isAccepted()
                    ) {
                        throw new ValidationException(
                            trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.csau.unit.pending'),
                            'appearance_cases.video_chat.unit_status_id'
                        );
                    }
                }
            }

            /**
             * Getting description status
             */
            $descriptionStatus = RequestFieldStatusList::getItem(
                $videoChat['description_status_id'] ?? null
            );

            /**
             * Checking description status existence
             */
            if ($descriptionStatus) {

                /**
                 * Checking description existence
                 */
                if (!$appearanceCase->description) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.descriptionStatus'),
                        'appearance_cases.video_chat.description_status_id'
                    );
                }
            }

            /**
             * Getting platform status
             */
            $platformStatus = RequestFieldStatusList::getItem(
                $videoChat['platform_status_id'] ?? null
            );

            /**
             * Checking platform status existence
             */
            if ($platformStatus) {

                /**
                 * Checking platforms existence
                 */
                if (!$appearanceCase->platforms_ids) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.platformsStatus'),
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

            /** @var VybePublishRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybePublishRequest->appearanceCases
                ->where('appearance_id', VybeAppearanceList::getRealLife()->id)
                ->first();

            /**
             * Checking appearance case existence
             */
            if (!$appearanceCase) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.realLife'),
                    'appearance_cases.real_life.absence'
                );
            }

            /**
             * Getting CSAU suggestion
             */
            $csauSuggestion = $appearanceCase->csauSuggestion;

            /**
             * Getting price status
             */
            $priceStatus = RequestFieldStatusList::getItem(
                $realLife['price_status_id']
            );

            /**
             * Checking price status existence
             */
            if (!$priceStatus) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.priceStatus'),
                    'appearance_cases.real_life.price_status_id'
                );
            }

            /**
             * Getting unit status
             */
            $unitStatus = RequestFieldStatusList::getItem(
                $realLife['unit_status_id']
            );

            /**
             * Checking unit status existence
             */
            if (!$unitStatus) {
                throw new ValidationException(
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.unitStatus'),
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
                    if ($csauSuggestion->getUnitStatus()->isPending() &&
                        $unitStatus->isAccepted()
                    ) {
                        throw new ValidationException(
                            trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.csau.unit.pending'),
                            'appearance_cases.real_life.unit_status_id'
                        );
                    }
                }
            }

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
                    trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.descriptionStatus'),
                    'appearance_cases.real_life.description_status_id'
                );
            }

            if ($appearanceCase->getAppearance()->isRealLife()) {

                /**
                 * Checking location status existence
                 */
                if (!isset($realLife['city_place_status_id'])) {
                    throw new ValidationException(
                        trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.cityPlaceStatus'),
                        'appearance_cases.real_life.city_place_status_id'
                    );
                }
            }
        }

        return true;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param array $appearanceCases
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateAppearanceCasesStatuses(
        VybePublishRequest $vybePublishRequest,
        array $appearanceCases
    ) : Collection
    {
        /**
         * Preparing vybe publish request appearance cases collection
         */
        $vybePublishRequestAppearanceCases = new Collection();

        /**
         * Checking voice chat existence
         */
        if (isset($appearanceCases['voice_chat'])) {

            /**
             * Getting voice chat
             */
            $voiceChat = $appearanceCases['voice_chat'];

            /** @var VybePublishRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybePublishRequest->appearanceCases
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
                    $voiceChat['price_status_id']
                );

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $voiceChat['unit_status_id']
                );

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $voiceChat['description_status_id']
                );

                /**
                 * Getting platforms status
                 */
                $platformsStatus = RequestFieldStatusList::getItem(
                    $voiceChat['platforms_status_id']
                );

                /**
                 * Updating vybe publish request appearance case
                 */
                $vybePublishRequestAppearanceCase = $this->vybePublishRequestAppearanceCaseRepository->updateStatuses(
                    $appearanceCase,
                    $priceStatus,
                    $unitStatus,
                    $descriptionStatus,
                    $platformsStatus,
                    null
                );

                /**
                 * Adding vybe publish request appearance case to a collection
                 */
                $vybePublishRequestAppearanceCases->push(
                    $vybePublishRequestAppearanceCase
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

            /** @var VybePublishRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybePublishRequest->appearanceCases
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
                    $videoChat['price_status_id']
                );

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $videoChat['unit_status_id']
                );

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $videoChat['description_status_id']
                );

                /**
                 * Getting platforms status
                 */
                $platformsStatus = RequestFieldStatusList::getItem(
                    $videoChat['platforms_status_id']
                );

                /**
                 * Updating vybe publish request appearance case
                 */
                $vybePublishRequestAppearanceCase = $this->vybePublishRequestAppearanceCaseRepository->updateStatuses(
                    $appearanceCase,
                    $priceStatus,
                    $unitStatus,
                    $descriptionStatus,
                    $platformsStatus,
                    null
                );

                /**
                 * Adding vybe publish request appearance case to a collection
                 */
                $vybePublishRequestAppearanceCases->push(
                    $vybePublishRequestAppearanceCase
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

            /** @var VybePublishRequestAppearanceCase $appearanceCase */
            $appearanceCase = $vybePublishRequest->appearanceCases
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
                    $realLife['price_status_id']
                );

                /**
                 * Getting unit status
                 */
                $unitStatus = RequestFieldStatusList::getItem(
                    $realLife['unit_status_id']
                );

                /**
                 * Getting description status
                 */
                $descriptionStatus = RequestFieldStatusList::getItem(
                    $realLife['description_status_id']
                );

                /**
                 * Getting city place status
                 */
                $cityPlaceStatus = RequestFieldStatusList::getItem(
                    $realLife['city_place_status_id']
                );

                /**
                 * Updating vybe publish request appearance case
                 */
                $vybePublishRequestAppearanceCase = $this->vybePublishRequestAppearanceCaseRepository->updateStatuses(
                    $appearanceCase,
                    $priceStatus,
                    $unitStatus,
                    $descriptionStatus,
                    null,
                    $cityPlaceStatus
                );

                /**
                 * Adding vybe publish request appearance case to a collection
                 */
                $vybePublishRequestAppearanceCases->push(
                    $vybePublishRequestAppearanceCase
                );
            }
        }

        return $vybePublishRequestAppearanceCases;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param array $declinedImagesIds
     * @param array $declinedVideosIds
     *
     * @return RequestStatusListItem
     */
    public function getRequestStatus(
        VybePublishRequest $vybePublishRequest,
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
         * Checking all vybe publish request statuses
         */
        if ($vybePublishRequest->getTitleStatus()->isDeclined() ||
            $vybePublishRequest->getCategoryStatus()->isDeclined() ||
            $vybePublishRequest->getSubcategoryStatus() && $vybePublishRequest->getSubcategoryStatus()->isDeclined() ||
            $vybePublishRequest->getDevicesStatus() && $vybePublishRequest->getDevicesStatus()->isDeclined() ||
            $vybePublishRequest->getActivityStatus()->isDeclined() ||
            $vybePublishRequest->getPeriodStatus()->isDeclined() ||
            $vybePublishRequest->getUserCountStatus()->isDeclined() ||
            $vybePublishRequest->getSchedulesStatus()->isDeclined() ||
            $vybePublishRequest->getAccessStatus()->isDeclined() ||
            $vybePublishRequest->getShowcaseStatus()->isDeclined() ||
            $vybePublishRequest->getOrderAcceptStatus()->isDeclined() ||
            $vybePublishRequest->getStatusStatus()->isDeclined()
        ) {
            return RequestStatusList::getDeclinedItem();
        }

        /**
         * Checking vybe publish request type
         */
        if (!$vybePublishRequest->getType()->isEvent()) {
            if ($vybePublishRequest->getOrderAdvanceStatus()->isDeclined()) {
                return RequestStatusList::getDeclinedItem();
            }
        }

        /** @var VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase */
        foreach ($vybePublishRequest->appearanceCases as $vybePublishRequestAppearanceCase) {

            /**
             * Checking vybe publish request appearance case price and unit statuses
             */
            if ($vybePublishRequestAppearanceCase->getPriceStatus()->isDeclined() ||
                $vybePublishRequestAppearanceCase->getUnitStatus()->isDeclined()
            ) {
                return RequestStatusList::getDeclinedItem();
            }

            /**
             * Checking vybe publish request appearance case description status
             */
            if ($vybePublishRequestAppearanceCase->getDescriptionStatus()) {
                if ($vybePublishRequestAppearanceCase->getDescriptionStatus()->isDeclined()) {
                    return RequestStatusList::getDeclinedItem();
                }
            }

            /**
             * Checking vybe publish request appearance case platforms status
             */
            if ($vybePublishRequestAppearanceCase->getPlatformsStatus()) {
                if ($vybePublishRequestAppearanceCase->getPlatformsStatus()->isDeclined()) {
                    return RequestStatusList::getDeclinedItem();
                }
            }

            /**
             * Checking vybe publish request appearance case city place status
             */
            if ($vybePublishRequestAppearanceCase->getCityPlaceStatus()) {
                if ($vybePublishRequestAppearanceCase->getCityPlaceStatus()->isDeclined()) {
                    return RequestStatusList::getDeclinedItem();
                }
            }
        }

        return RequestStatusList::getAcceptedItem();
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @throws DatabaseException
     */
    public function deleteAllForPublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void
    {
        /**
         * Deleting vybe publish request appearance cases
         */
        $this->vybePublishRequestAppearanceCaseRepository->deleteForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Deleting vybe publish request schedules
         */
        $this->vybePublishRequestScheduleRepository->deleteForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Deleting vybe publish request
         */
        $this->vybePublishRequestRepository->delete(
            $vybePublishRequest
        );
    }

    /**
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCaseStatus(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
    ) : RequestFieldStatusListItem
    {
        $statusesIds = [];

        if ($vybePublishRequestAppearanceCase->getPriceStatus()) {
            if ($vybePublishRequestAppearanceCase->getPriceStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybePublishRequestAppearanceCase->getPriceStatus()->id;
            }
        }

        if ($vybePublishRequestAppearanceCase->getUnitStatus()) {
            if ($vybePublishRequestAppearanceCase->getUnitStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybePublishRequestAppearanceCase->getUnitStatus()->id;
            }
        }

        if ($vybePublishRequestAppearanceCase->getDescriptionStatus()) {
            if ($vybePublishRequestAppearanceCase->getDescriptionStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybePublishRequestAppearanceCase->getDescriptionStatus()->id;
            }
        }

        if ($vybePublishRequestAppearanceCase->getPlatformsStatus()) {
            if ($vybePublishRequestAppearanceCase->getPlatformsStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybePublishRequestAppearanceCase->getPlatformsStatus()->id;
            }
        }

        if ($vybePublishRequestAppearanceCase->getCityPlaceStatus()) {
            if ($vybePublishRequestAppearanceCase->getCityPlaceStatus()->isDeclined()) {
                return RequestFieldStatusList::getDeclinedItem();
            } else {
                $statusesIds[] = $vybePublishRequestAppearanceCase->getCityPlaceStatus()->id;
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
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCasesStatus(
        VybePublishRequest $vybePublishRequest
    ) : RequestFieldStatusListItem
    {
        $statusesIds = [];

        /** @var VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase */
        foreach ($vybePublishRequest->appearanceCases as $vybePublishRequestAppearanceCase) {
            $requestFieldStatus = $this->getAppearanceCaseStatus(
                $vybePublishRequestAppearanceCase
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
     * @param Collection|null $vybePublishRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybePublishRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking vybe publish requests existence
             */
            if ($vybePublishRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->vybePublishRequestRepository->getRequestStatusCountByIds(
                    $vybePublishRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->vybePublishRequestRepository->getRequestStatusCount(
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
                                trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.image'),
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
                                trans('exceptions/service/vybe/vybePublishRequest.' . __FUNCTION__ . '.video'),
                                'videos.' . $video->id
                            );
                        }
                    }
                }
            }
        }
    }
}
