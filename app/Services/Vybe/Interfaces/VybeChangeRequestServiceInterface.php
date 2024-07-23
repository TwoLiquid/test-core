<?php

namespace App\Services\Vybe\Interfaces;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeChangeRequestServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybeChangeRequestServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function checkVybeAvailability(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param array $data
     * @param array|null $changedImagesIds
     * @param array|null $changedVideosIds
     *
     * @return bool
     */
    public function hasVybeChangeRequestChanges(
        Vybe $vybe,
        array $data,
        ?array $changedImagesIds,
        ?array $changedVideosIds
    ) : bool;

    /**
     * This method provides creating data
     *
     * @param Vybe $vybe
     * @param array $data
     * @param array|null $changedImagesIds
     * @param array|null $changedVideosIds
     *
     * @return VybeChangeRequest|null
     */
    public function createVybeChangeRequest(
        Vybe $vybe,
        array $data,
        ?array $changedImagesIds,
        ?array $changedVideosIds
    ) : ?VybeChangeRequest;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return bool
     */
    public function haveAppearanceCasesChanges(
        Vybe $vybe,
        array $appearanceCases
    ) : bool;

    /**
     * This method provides creating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function createAppearanceCases(
        VybeChangeRequest $vybeChangeRequest,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param array $schedulesItems
     *
     * @return bool
     */
    public function haveSchedulesChanges(
        Vybe $vybe,
        array $schedulesItems
    ) : bool;

    /**
     * This method provides creating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $schedulesItems
     *
     * @return Collection
     */
    public function createSchedules(
        VybeChangeRequest $vybeChangeRequest,
        array $schedulesItems
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return VybeChangeRequest
     */
    public function updateByCsauSuggestions(
        VybeChangeRequest $vybeChangeRequest
    ) : VybeChangeRequest;

    /**
     * This method provides updating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybeChangeRequest
     */
    public function updateByDeviceSuggestion(
        VybeChangeRequest $vybeChangeRequest,
        DeviceSuggestion $deviceSuggestion
    ) : VybeChangeRequest;

    /**
     * This method provides checking data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $appearanceCases
     *
     * @return bool
     */
    public function validateAppearanceCasesStatuses(
        VybeChangeRequest $vybeChangeRequest,
        array $appearanceCases
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function updateAppearanceCasesStatuses(
        VybeChangeRequest $vybeChangeRequest,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides getting data
     *
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
    ) : RequestStatusListItem;

    /**
     * This method provides deleting data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     */
    public function deleteAllForChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void;

    /**
     * This method provides getting data
     *
     * @param VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCaseStatus(
        VybeChangeRequestAppearanceCase $vybeChangeRequestAppearanceCase
    ) : RequestFieldStatusListItem;

    /**
     * This method provides getting data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCasesStatus(
        VybeChangeRequest $vybeChangeRequest
    ) : RequestFieldStatusListItem;

    /**
     * This method provides getting data
     *
     * @param Collection|null $vybeChangeRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeChangeRequests
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param Collection $images
     * @param array $acceptedImagesIds
     * @param array $declinedImagesIds
     */
    public function checkImagesAreProcessed(
        Collection $images,
        array $acceptedImagesIds,
        array $declinedImagesIds
    ) : void;

    /**
     * This method provides checking data
     *
     * @param Collection $videos
     * @param array $acceptedVideosIds
     * @param array $declinedVideosIds
     */
    public function checkVideosAreProcessed(
        Collection $videos,
        array $acceptedVideosIds,
        array $declinedVideosIds
    ) : void;
}