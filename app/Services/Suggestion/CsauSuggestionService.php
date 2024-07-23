<?php

namespace App\Services\Suggestion;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Unit\UnitRepository;
use App\Repositories\Vybe\VybeChangeRequestAppearanceCaseRepository;
use App\Repositories\Vybe\VybePublishRequestAppearanceCaseRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Suggestion\Interfaces\CsauSuggestionServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CsauSuggestionService
 *
 * @package App\Services\Suggestion
 */
class CsauSuggestionService implements CsauSuggestionServiceInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var VybeChangeRequestAppearanceCaseRepository
     */
    protected VybeChangeRequestAppearanceCaseRepository $vybeChangeRequestAppearanceCaseRepository;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybePublishRequestAppearanceCaseRepository
     */
    protected VybePublishRequestAppearanceCaseRepository $vybePublishRequestAppearanceCaseRepository;

    /**
     * CsauSuggestionService constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var VybeChangeRequestAppearanceCaseRepository vybeChangeRequestAppearanceCaseRepository */
        $this->vybeChangeRequestAppearanceCaseRepository = new VybeChangeRequestAppearanceCaseRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybePublishRequestAppearanceCaseRepository vybePublishRequestAppearanceCaseRepository */
        $this->vybePublishRequestAppearanceCaseRepository = new VybePublishRequestAppearanceCaseRepository();
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function executeForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection
    {
        /**
         * Preparing CSAU suggestions collection
         */
        $csauSuggestions = new Collection();

        /** @var VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase */
        foreach ($vybePublishRequest->appearanceCases as $vybePublishRequestAppearanceCase) {

            /**
             * Checking is at least one of the suggestions exists
             */
            if (!is_null($vybePublishRequest->category_suggestion) ||
                !is_null($vybePublishRequest->subcategory_suggestion) ||
                !is_null($vybePublishRequest->activity_suggestion) ||
                !is_null($vybePublishRequestAppearanceCase->unit_suggestion)
            ) {

                /**
                 * Creating CSAU suggestion
                 */
                $csauSuggestion = $this->csauSuggestionRepository->store(
                    $vybePublishRequest,
                    null,
                    $vybePublishRequest->category,
                    $vybePublishRequest->category_suggestion,
                    $vybePublishRequest->subcategory,
                    $vybePublishRequest->subcategory_suggestion,
                    $vybePublishRequest->activity,
                    $vybePublishRequest->activity_suggestion,
                    $vybePublishRequestAppearanceCase->unit,
                    $vybePublishRequestAppearanceCase->unit_suggestion
                );

                /**
                 * Checking is CSAU suggestion has been created
                 */
                if ($csauSuggestion) {

                    /**
                     * Updating vybe publish request appearance case CSAU suggestion
                     */
                    $this->vybePublishRequestAppearanceCaseRepository->updateCsauSuggestion(
                        $vybePublishRequestAppearanceCase,
                        $csauSuggestion
                    );

                    /**
                     * Adding CSAU suggestion to a collection
                     */
                    $csauSuggestions->add(
                        $csauSuggestion
                    );
                }

                /**
                 * Releasing suggestion cache
                 */
                $this->adminNavbarService->releaseAllSuggestionCache();
            }
        }

        return $csauSuggestions;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function executeForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection
    {
        /**
         * Preparing CSAU suggestions collection
         */
        $csauSuggestions = new Collection();

        /** @var VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase */
        foreach ($vybeChangeRequest->appearanceCases as $vybeChangeRequestAppearanceCase) {

            /**
             * Checking is at least one of the suggestions exists
             */
            if (!is_null($vybeChangeRequest->category_suggestion) ||
                !is_null($vybeChangeRequest->subcategory_suggestion) ||
                !is_null($vybeChangeRequest->activity_suggestion) ||
                !is_null($vybeChangeRequestAppearanceCase->unit_suggestion)
            ) {

                /**
                 * Getting category
                 */
                $category = $vybeChangeRequest->category;

                /**
                 * Checking category existence
                 */
                if (!$category) {

                    /**
                     * Checking category suggestion existence
                     */
                    if (!$vybeChangeRequest->category_suggestion) {

                        /**
                         * Getting category
                         */
                        $category = $vybeChangeRequest->vybe->activity->category->parent ?
                            $vybeChangeRequest->vybe->activity->category->parent :
                            $vybeChangeRequest->vybe->activity->category;
                    }
                }

                /**
                 * Getting subcategory
                 */
                $subcategory = $vybeChangeRequest->subcategory;

                /**
                 * Checking subcategory existence
                 */
                if (!$subcategory) {

                    /**
                     * Checking subcategory suggestion existence
                     */
                    if (!$vybeChangeRequest->subcategory_suggestion) {

                        /**
                         * Getting subcategory
                         */
                        $subcategory = $vybeChangeRequest->vybe->activity->category->parent ?
                            $vybeChangeRequest->vybe->activity->category :
                            null;
                    }
                }

                /**
                 * Getting activity
                 */
                $activity = $vybeChangeRequest->activity;

                /**
                 * Checking activity existence
                 */
                if (!$activity) {

                    /**
                     * Checking activity suggestion existence
                     */
                    if (!$vybeChangeRequest->activity_suggestion) {

                        /**
                         * Getting activity
                         */
                        $activity = $vybeChangeRequest->vybe
                            ->activity;
                    }
                }

                /**
                 * Creating CSAU suggestion
                 */
                $csauSuggestion = $this->csauSuggestionRepository->store(
                    null,
                    $vybeChangeRequest,
                    $category,
                    $vybeChangeRequest->category_suggestion,
                    $subcategory,
                    $vybeChangeRequest->subcategory_suggestion,
                    $activity,
                    $vybeChangeRequest->activity_suggestion,
                    $vybeChangeRequestAppearanceCase->unit,
                    $vybeChangeRequestAppearanceCase->unit_suggestion
                );

                /**
                 * Checking is CSAU suggestion has been created
                 */
                if ($csauSuggestion) {

                    /**
                     * Updating vybe change request appearance case CSAU suggestion
                     */
                    $this->vybeChangeRequestAppearanceCaseRepository->updateCsauSuggestion(
                        $vybeChangeRequestAppearanceCase,
                        $csauSuggestion
                    );

                    /**
                     * Adding CSAU suggestion to a collection
                     */
                    $csauSuggestions->add(
                        $csauSuggestion
                    );
                }

                /**
                 * Releasing suggestion cache
                 */
                $this->adminNavbarService->releaseAllSuggestionCache();
            }
        }

        return $csauSuggestions;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     */
    public function isFullyAccepted(
        CsauSuggestion $csauSuggestion
    ) : bool
    {
        /**
         * Checking is CSAU suggestion category status accepted
         */
        if (!$csauSuggestion->getCategoryStatus() ||
            !$csauSuggestion->getCategoryStatus()->isAccepted()
        ) {
            return false;
        }

        /**
         * Checking is CSAU suggestion subcategory status accepted
         */
        if ($csauSuggestion->getSubcategoryStatus() &&
            !$csauSuggestion->getSubcategoryStatus()->isAccepted()
        ) {
            return false;
        }

        /**
         * Checking is CSAU suggestion activity status accepted
         */
        if (!$csauSuggestion->getActivityStatus() ||
            !$csauSuggestion->getActivityStatus()->isAccepted()
        ) {
            return false;
        }

        /**
         * Checking is CSAU suggestion unit status accepted
         */
        if (!$csauSuggestion->getUnitStatus() ||
            !$csauSuggestion->getUnitStatus()->isAccepted()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     */
    public function isPartlyDeclined(
        CsauSuggestion $csauSuggestion
    ) : bool
    {
        /**
         * Checking is CSAU suggestion category status declined
         */
        if ($csauSuggestion->getCategoryStatus() &&
            $csauSuggestion->getCategoryStatus()->isDeclined()
        ) {
            return true;
        }

        /**
         * Checking is CSAU suggestion subcategory status declined
         */
        if ($csauSuggestion->getSubcategoryStatus() &&
            $csauSuggestion->getSubcategoryStatus()->isDeclined()
        ) {
            return true;
        }

        /**
         * Checking is CSAU suggestion activity status declined
         */
        if ($csauSuggestion->getActivityStatus() &&
            $csauSuggestion->getActivityStatus()->isDeclined()
        ) {
            return true;
        }

        /**
         * Checking is CSAU suggestion unit status declined
         */
        if ($csauSuggestion->getUnitStatus() &&
            $csauSuggestion->getUnitStatus()->isDeclined()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @throws DatabaseException
     */
    public function processForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void
    {
        /**
         * Getting CSAU suggestions for vybe publish request
         */
        $csauSuggestions = $this->csauSuggestionRepository->getAllForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Checking CSAU suggestions existence
         */
        if ($csauSuggestions->count()) {

            /** @var CsauSuggestion $csauSuggestion */
            foreach ($csauSuggestions as $csauSuggestion) {

                /**
                 * Checking is CSAU suggestion fully accepted
                 */
                if ($this->isFullyAccepted($csauSuggestion)) {

                    /**
                     * Updating CSAU suggestion
                     */
                    $this->csauSuggestionRepository->updateStatus(
                        $csauSuggestion,
                        RequestStatusList::getAcceptedItem()
                    );
                }

                /**
                 * Checking CSAU suggestion partly declined
                 */
                if ($this->isPartlyDeclined($csauSuggestion)) {

                    /**
                     * Updating CSAU suggestion
                     */
                    $this->csauSuggestionRepository->updateStatus(
                        $csauSuggestion,
                        RequestStatusList::getDeclinedItem()
                    );
                }

                /**
                 * Releasing suggestion cache
                 */
                $this->adminNavbarService->releaseAllSuggestionCache();
            }
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function processForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void
    {
        /**
         * Getting CSAU suggestions for vybe change request
         */
        $csauSuggestions = $this->csauSuggestionRepository->getAllForVybeChangeRequest(
            $vybeChangeRequest
        );

        /**
         * Checking CSAU suggestions existence
         */
        if ($csauSuggestions->count()) {

            /** @var CsauSuggestion $csauSuggestion */
            foreach ($csauSuggestions as $csauSuggestion) {

                /**
                 * Checking is CSAU suggestion fully accepted
                 */
                if ($this->isFullyAccepted($csauSuggestion)) {

                    /**
                     * Updating CSAU suggestion
                     */
                    $this->csauSuggestionRepository->updateStatus(
                        $csauSuggestion,
                        RequestStatusList::getAcceptedItem()
                    );
                }

                /**
                 * Checking CSAU suggestion partly declined
                 */
                if ($this->isPartlyDeclined($csauSuggestion)) {

                    /**
                     * Updating CSAU suggestion
                     */
                    $this->csauSuggestionRepository->updateStatus(
                        $csauSuggestion,
                        RequestStatusList::getDeclinedItem()
                    );
                }

                /**
                 * Releasing suggestion cache
                 */
                $this->adminNavbarService->releaseAllSuggestionCache();
            }
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllVybePublishRequestPlatforms(
        VybePublishRequest $vybePublishRequest
    ) : Collection
    {
        $platformsIds = [];

        /** @var VybePublishRequestAppearanceCase $appearanceCase */
        foreach ($vybePublishRequest->appearanceCases as $appearanceCase) {
            if ($appearanceCase->platforms_ids) {

                /** @var int $platformId */
                foreach ($appearanceCase->platforms_ids as $platformId) {
                    if (!in_array($platformId, $platformsIds)) {
                        $platformsIds[] = $platformId;
                    }
                }
            }
        }

        return $this->platformRepository->getByIds(
            $platformsIds
        );
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllVybeChangeRequestPlatforms(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection
    {
        $platformsIds = [];

        /** @var VybeChangeRequestAppearanceCase $appearanceCase */
        foreach ($vybeChangeRequest->appearanceCases as $appearanceCase) {
            if ($appearanceCase->platforms_ids) {

                /** @var int $platformId */
                foreach ($appearanceCase->platforms_ids as $platformId) {
                    if (!in_array($platformId, $platformsIds)) {
                        $platformsIds[] = $platformId;
                    }
                }
            }
        }

        return $this->platformRepository->getByIds(
            $platformsIds
        );
    }
}
