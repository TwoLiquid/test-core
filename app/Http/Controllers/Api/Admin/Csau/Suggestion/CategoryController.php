<?php

namespace App\Http\Controllers\Api\Admin\Csau\Suggestion;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Csau\Suggestion\Interfaces\CategoryControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Suggestion\Category\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Suggestion\Category\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Unit\Type\UnitTypeList;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Unit\UnitRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Suggestion\CsauSuggestionService;
use App\Services\Vybe\VybeChangeRequestService;
use App\Services\Vybe\VybePublishRequestService;
use App\Transformers\Api\Admin\Csau\Suggestion\CsauSuggestionTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Suggestion
 */
final class CategoryController extends BaseController implements CategoryControllerInterface
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
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var CsauSuggestionService
     */
    protected CsauSuggestionService $csauSuggestionService;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * @var VybePublishRequestService
     */
    protected VybePublishRequestService $vybePublishRequestService;

    /**
     * CategoryController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var CsauSuggestionService csauSuggestionService */
        $this->csauSuggestionService = new CsauSuggestionService();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking paginated enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting CSAU suggestions
             */
            $csauSuggestions = $this->csauSuggestionRepository->getAllPendingPaginated(
                $request->input('date_from'),
                $request->input('date_to'),
                $request->input('username'),
                $request->input('vybe_version'),
                $request->input('vybe_title'),
                $request->input('categories_ids'),
                $request->input('subcategories_ids'),
                $request->input('activities_ids'),
                $request->input('unit_types_ids'),
                $request->input('units_ids'),
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($csauSuggestions)->respondWithSuccess(
                $this->transformCollection($csauSuggestions, new CsauSuggestionTransformer),
                trans('validations/api/admin/csau/suggestion/category/index.result.success')
            );
        }

        /**
         * Getting CSAU suggestions
         */
        $csauSuggestions = $this->csauSuggestionRepository->getAllPending(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('vybe_version'),
            $request->input('vybe_title'),
            $request->input('categories_ids'),
            $request->input('subcategories_ids'),
            $request->input('activities_ids'),
            $request->input('unit_types_ids'),
            $request->input('units_ids')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($csauSuggestions, new CsauSuggestionTransformer),
            trans('validations/api/admin/csau/suggestion/category/index.result.success')
        );
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting CSAU suggestion
         */
        $csauSuggestion = $this->csauSuggestionRepository->findFullById($id);

        if (!$csauSuggestion) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/show.result.error.find')
            );
        }

        /**
         * Checking is CSAU suggestion pending
         */
        if (!$csauSuggestion->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/show.result.error.processed')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($csauSuggestion, new CsauSuggestionTransformer),
            trans('validations/api/admin/csau/suggestion/category/show.result.success')
        );
    }

    /**
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting CSAU suggestion
         */
        $csauSuggestion = $this->csauSuggestionRepository->findFullById($id);

        if (!$csauSuggestion) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/update.result.error.find')
            );
        }

        /**
         * Checking is CSAU suggestion pending
         */
        if (!$csauSuggestion->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/update.result.error.processed')
            );
        }

        /**
         * Getting category status
         */
        $categoryStatusListItem = RequestFieldStatusList::getItem(
            $request->input('category_status_id')
        );

        /**
         * Checking category suggestion and status existence
         */
        if (!$csauSuggestion->category &&
            $csauSuggestion->category_suggestion &&
            !$categoryStatusListItem
        ) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/update.category_status_id.required')
            );
        }

        /**
         * Getting subcategory status
         */
        $subcategoryStatusListItem = RequestFieldStatusList::getItem(
            $request->input('subcategory_status_id')
        );

        /**
         * Checking subcategory suggestion and status existence
         */
        if (!$csauSuggestion->subcategory &&
            $csauSuggestion->subcategory_suggestion &&
            !$subcategoryStatusListItem
        ) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/update.subcategory_status_id.required')
            );
        }

        /**
         * Getting activity status
         */
        $activityStatusListItem = RequestFieldStatusList::getItem(
            $request->input('activity_status_id')
        );

        /**
         * Checking activity suggestion and status existence
         */
        if (!$csauSuggestion->activity &&
            $csauSuggestion->activity_suggestion &&
            !$activityStatusListItem
        ) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/update.activity_status_id.required')
            );
        }

        /**
         * Getting unit status
         */
        $unitStatusListItem = RequestFieldStatusList::getItem(
            $request->input('unit_status_id')
        );

        /**
         * Checking unit suggestion and status existence
         */
        if ($csauSuggestion->unit_suggestion &&
            !$unitStatusListItem
        ) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/category/update.unit_status_id.required')
            );
        }

        /**
         * Checking is unit accepted
         */
        if ($unitStatusListItem &&
            $unitStatusListItem->isAccepted()
        ) {

            /**
             * Checking is activity declined while unit is accepted
             */
            if ($activityStatusListItem &&
                $activityStatusListItem->isDeclined()
            ) {
                return $this->respondWithError(
                    trans('validations/api/admin/csau/suggestion/category/update.activity_status_id.declined')
                );
            } elseif (
                $activityStatusListItem &&
                $activityStatusListItem->isAccepted()
            ) {

                /**
                 * Checking is subcategory declined while activity is accepted
                 */
                if ($subcategoryStatusListItem &&
                    $subcategoryStatusListItem->isDeclined()
                ) {
                    return $this->respondWithError(
                        trans('validations/api/admin/csau/suggestion/category/update.subcategory_status_id.declined')
                    );
                } elseif (
                    $subcategoryStatusListItem &&
                    $subcategoryStatusListItem->isAccepted()
                ) {

                    /**
                     * Checking is category declined while subcategory is accepted
                     */
                    if ($categoryStatusListItem &&
                        $categoryStatusListItem->isDeclined()
                    ) {
                        return $this->respondWithError(
                            trans('validations/api/admin/csau/suggestion/category/update.category_status_id.declined')
                        );
                    }
                }
            }
        }

        /**
         * Checking CSAU suggestion vybe publish request existence
         */
        if ($csauSuggestion->vybePublishRequest) {

            /**
             * Checking is vybe publish request is not for event vybe
             */
            if (!$csauSuggestion->vybePublishRequest->getType()->isEvent() &&
                ($unitStatusListItem && $unitStatusListItem->isAccepted()) &&
                !$request->input('unit_duration')
            ) {
                return $this->respondWithError(
                    trans('validations/api/admin/csau/suggestion/category/update.error.unit_duration')
                );
            }
        } elseif ($csauSuggestion->vybeChangeRequest) {

            /**
             * Checking unit status
             */
            if ($unitStatusListItem && $unitStatusListItem->isAccepted()) {

                /**
                 * Checking vybe type
                 */
                if (($csauSuggestion->vybeChangeRequest->getType() && !$csauSuggestion->vybeChangeRequest->getType()->isEvent()) ||
                    (!$csauSuggestion->vybeChangeRequest->getType() && !$csauSuggestion->vybeChangeRequest->vybe->getType()->isEvent())
                ) {

                    /**
                     * Checking unit duration existence
                     */
                    if (!$request->input('unit_duration')) {
                        return $this->respondWithError(
                            trans('validations/api/admin/csau/suggestion/category/update.error.unit_duration')
                        );
                    }
                }
            }
        }

        /**
         * Getting CSAU suggestion category
         */
        $category = $csauSuggestion->category;

        /**
         * Checking CSAU suggestion category
         */
        if (!$category) {

            /**
             * Checking category status acceptance
             */
            if ($categoryStatusListItem->isAccepted()) {

                /**
                 * Getting an actual category suggestion
                 */
                $categorySuggestion = $request->input('category_suggestion') ?
                    $request->input('category_suggestion') :
                    $csauSuggestion->category_suggestion;

                /**
                 * Getting category
                 */
                $category = $this->categoryRepository->findByName(
                    $request->input('category_suggestion')['en']
                );

                /**
                 * Checking category existence
                 */
                if (!$category) {

                    /**
                     * Creating category
                     */
                    $category = $this->categoryRepository->store(
                        null,
                        $categorySuggestion,
                        $request->input('visible')
                    );
                }

                if (!$category) {
                    return $this->respondWithError(
                        trans('validations/api/admin/csau/suggestion/category/update.result.error.category.create')
                    );
                }

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Accepting category for vybe publish request
                     */
                    $this->csauSuggestionRepository->acceptCategoryForVybePublishRequest(
                        $csauSuggestion->vybePublishRequest,
                        $category
                    );
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Accepting category for vybe change request
                     */
                    $this->csauSuggestionRepository->acceptCategoryForVybeChangeRequest(
                        $csauSuggestion->vybeChangeRequest,
                        $category
                    );
                }
            } else {

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Declining category for vybe publish request
                     */
                    $this->csauSuggestionRepository->declineCategoryForVybePublishRequest(
                        $csauSuggestion->vybePublishRequest
                    );
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Declining category for vybe change request
                     */
                    $this->csauSuggestionRepository->declineCategoryForVybeChangeRequest(
                        $csauSuggestion->vybeChangeRequest
                    );
                }
            }
        }

        /**
         * Getting CSAU suggestion subcategory
         */
        $subcategory = $csauSuggestion->subcategory;

        /**
         * Checking CSAU suggestion subcategory
         */
        if (!$subcategory && $csauSuggestion->subcategory_suggestion) {

            /**
             * Checking subcategory status acceptance
             */
            if ($subcategoryStatusListItem->isAccepted()) {

                /**
                 * Getting an actual subcategory suggestion
                 */
                $subcategorySuggestion = $request->input('subcategory_suggestion') ?
                    $request->input('subcategory_suggestion') :
                    $csauSuggestion->subcategory_suggestion;

                /**
                 * Getting subcategory
                 */
                $subcategory = $this->categoryRepository->findByName(
                    $request->input('subcategory_suggestion')['en']
                );

                /**
                 * Checking subcategory existence
                 */
                if (!$subcategory) {

                    /**
                     * Creating subcategory
                     */
                    $subcategory = $this->categoryRepository->store(
                        $category,
                        $subcategorySuggestion,
                        $request->input('visible')
                    );
                }

                if (!$subcategory) {
                    return $this->respondWithError(
                        trans('validations/api/admin/csau/suggestion/category/update.result.error.subcategory.create')
                    );
                }

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Accepting subcategory for vybe publish request
                     */
                    $this->csauSuggestionRepository->acceptSubcategoryForVybePublishRequest(
                        $csauSuggestion->vybePublishRequest,
                        $subcategory
                    );
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Accepting subcategory for vybe change request
                     */
                    $this->csauSuggestionRepository->acceptSubcategoryForVybeChangeRequest(
                        $csauSuggestion->vybeChangeRequest,
                        $subcategory
                    );
                }
            } else {

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Declining subcategory for vybe publish request
                     */
                    $this->csauSuggestionRepository->declineSubcategoryForVybePublishRequest(
                        $csauSuggestion->vybePublishRequest
                    );
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Declining subcategory for vybe change request
                     */
                    $this->csauSuggestionRepository->declineSubcategoryForVybeChangeRequest(
                        $csauSuggestion->vybeChangeRequest
                    );
                }
            }
        }

        /**
         * Getting CSAU suggestion activity
         */
        $activity = $csauSuggestion->activity;

        /**
         * Checking CSAU suggestion activity
         */
        if (!$activity) {

            /**
             * Checking activity status acceptance
             */
            if ($activityStatusListItem->isAccepted()) {

                /**
                 * Getting an actual activity suggestion
                 */
                $activitySuggestion = $request->input('activity_suggestion') ?
                    $request->input('activity_suggestion') :
                    $csauSuggestion->activity_suggestion;

                /**
                 * Getting activity
                 */
                $activity = $this->activityRepository->findByName(
                    $subcategory ?: $category,
                    $request->input('activity_suggestion')['en']
                );

                /**
                 * Checking activity existence
                 */
                if (!$activity) {

                    /**
                     * Creating activity
                     */
                    $activity = $this->activityRepository->store(
                        $subcategory ?: $category,
                        $activitySuggestion,
                        $request->input('visible')
                    );
                }

                if (!$activity) {
                    return $this->respondWithError(
                        trans('validations/api/admin/csau/suggestion/category/update.result.error.activity.create')
                    );
                }

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Accepting activity for vybe publish request
                     */
                    $this->csauSuggestionRepository->acceptActivityForVybePublishRequest(
                        $csauSuggestion->vybePublishRequest,
                        $activity
                    );

                    /**
                     * Checking devices existence
                     */
                    if ($csauSuggestion->vybePublishRequest->devices_ids) {

                        /**
                         * Attaching devices to activity
                         */
                        $this->activityRepository->attachDevices(
                            $activity,
                            $csauSuggestion->vybePublishRequest
                                ->devices_ids
                        );
                    }

                    /**
                     * Getting platforms
                     */
                    $platforms = $this->csauSuggestionService->getAllVybePublishRequestPlatforms(
                        $csauSuggestion->vybePublishRequest
                    );

                    /**
                     * Checking platforms
                     */
                    if ($platforms->count()) {

                        /**
                         * Attaching platforms to activity
                         */
                        $this->activityRepository->attachPlatforms(
                            $activity,
                            $platforms->pluck('id')
                                ->values()
                                ->toArray()
                        );
                    }
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Accepting activity for vybe change request
                     */
                    $this->csauSuggestionRepository->acceptActivityForVybeChangeRequest(
                        $csauSuggestion->vybeChangeRequest,
                        $activity
                    );

                    /**
                     * Checking devices existence
                     */
                    if ($csauSuggestion->vybeChangeRequest->devices_ids) {

                        /**
                         * Attaching devices to activity
                         */
                        $this->activityRepository->attachDevices(
                            $activity,
                            $csauSuggestion->vybeChangeRequest
                                ->devices_ids
                        );
                    }

                    /**
                     * Getting platforms
                     */
                    $platforms = $this->csauSuggestionService->getAllVybeChangeRequestPlatforms(
                        $csauSuggestion->vybeChangeRequest
                    );

                    /**
                     * Checking platforms
                     */
                    if ($platforms->count()) {

                        /**
                         * Attaching platforms to activity
                         */
                        $this->activityRepository->attachPlatforms(
                            $activity,
                            $platforms->pluck('id')
                                ->values()
                                ->toArray()
                        );
                    }
                }
            } else {

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Declining activity for vybe publish request
                     */
                    $this->csauSuggestionRepository->declineActivityForVybePublishRequest(
                        $csauSuggestion->vybePublishRequest
                    );
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Declining activity for vybe change request
                     */
                    $this->csauSuggestionRepository->declineActivityForVybeChangeRequest(
                        $csauSuggestion->vybeChangeRequest
                    );
                }
            }
        }

        /**
         * Checking CSAU suggestion unit
         */
        if (!$csauSuggestion->unit) {

            /**
             * Checking CSAU suggestion unit
             */
            if ($unitStatusListItem->isAccepted()) {

                /**
                 * Getting an actual unit suggestion
                 */
                $unitSuggestion = $request->input('unit_suggestion') ?
                    $request->input('unit_suggestion') :
                    $csauSuggestion->unit_suggestion;

                $isEvent = false;

                /**
                 * Checking CSAU suggestion vybe publish request existence
                 */
                if ($csauSuggestion->vybePublishRequest) {

                    /**
                     * Checking is vybe publish request is not for event vybe
                     */
                    if ($csauSuggestion->vybePublishRequest->getType()->isEvent()) {
                        $isEvent = true;
                    }
                } elseif ($csauSuggestion->vybeChangeRequest) {

                    /**
                     * Checking is vybe change request is not for event vybe
                     */
                    if (($csauSuggestion->vybeChangeRequest->getType() && $csauSuggestion->vybeChangeRequest->getType()->isEvent()) ||
                        (!$csauSuggestion->vybeChangeRequest->getType() && $csauSuggestion->vybeChangeRequest->vybe->getType()->isEvent())
                    ) {
                        $isEvent = true;
                    }
                }

                /**
                 * Getting unit
                 */
                $unit = $this->unitRepository->findByName(
                    $unitSuggestion['en']
                );

                /**
                 * Checking unit existence
                 */
                if (!$unit) {

                    /**
                     * Creating unit
                     */
                    $unit = $this->unitRepository->store(
                        $isEvent ?
                            UnitTypeList::getEvent() :
                            UnitTypeList::getUsual(),
                        $unitSuggestion,
                        !$isEvent ?$request->input('unit_duration') : null,
                        $request->input('visible')
                    );
                }

                /**
                 */
                if (!$unit) {
                    return $this->respondWithError(
                        trans('validations/api/admin/csau/suggestion/category/update.result.error.unit.create')
                    );
                }

                /**
                 * Checking is unit event
                 */
                if (!$isEvent) {

                    /**
                     * Attaching unit to activity
                     */
                    $this->activityRepository->attachUnit(
                        $activity,
                        $unit
                    );
                }

                /**
                 * Updating CSAU suggestion unit
                 */
                $this->csauSuggestionRepository->updateUnit(
                    $csauSuggestion,
                    $unit
                );

                /**
                 * Updating CSAU suggestion unit status
                 */
                $this->csauSuggestionRepository->updateUnitStatus(
                    $csauSuggestion,
                    RequestFieldStatusList::getAcceptedItem()
                );
            } else {

                /**
                 * Updating CSAU suggestion unit status
                 */
                $this->csauSuggestionRepository->updateUnitStatus(
                    $csauSuggestion,
                    RequestFieldStatusList::getDeclinedItem()
                );
            }
        }

        /**
         * Checking CSAU suggestion vybe publish request existence
         */
        if ($csauSuggestion->vybePublishRequest) {

            /**
             * Processing all vybe publish request CSAU suggestions
             */
            $this->csauSuggestionService->processForVybePublishRequest(
                $csauSuggestion->vybePublishRequest
            );

            /**
             * Update vybe publish request with CSAU suggestions
             */
            $this->vybePublishRequestService->updateByCsauSuggestions(
                $csauSuggestion->vybePublishRequest
            );
        } elseif ($csauSuggestion->vybeChangeRequest) {

            /**
             * Processing all vybe change request CSAU suggestions
             */
            $this->csauSuggestionService->processForVybeChangeRequest(
                $csauSuggestion->vybeChangeRequest
            );

            /**
             * Update vybe change request with CSAU suggestions
             */
            $this->vybeChangeRequestService->updateByCsauSuggestions(
                $csauSuggestion->vybeChangeRequest
            );
        }

        /**
         * Releasing all suggestion caches
         */
        $this->adminNavbarService->releaseAllSuggestionCache();

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->csauSuggestionRepository->findFullById(
                    $csauSuggestion->_id
                ), new CsauSuggestionTransformer
            ), trans('validations/api/admin/csau/suggestion/category/update.result.success')
        );
    }
}
