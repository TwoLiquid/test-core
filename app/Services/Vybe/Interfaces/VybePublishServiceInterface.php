<?php

namespace App\Services\Vybe\Interfaces;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybePublishServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybePublishServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param Vybe $vybe
     *
     * @return VybePublishRequest|null
     */
    public function executePublishRequestForVybe(
        Vybe $vybe
    ) : ?VybePublishRequest;

    /**
     * This method provides updating data
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return VybePublishRequest
     */
    public function updateByCsauSuggestions(
        VybePublishRequest $vybePublishRequest
    ) : VybePublishRequest;

    /**
     * This method provides updating data
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybePublishRequest
     */
    public function updateByDeviceSuggestion(
        VybePublishRequest $vybePublishRequest,
        DeviceSuggestion $deviceSuggestion
    ) : VybePublishRequest;

    /**
     * This method provides updating data
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param array $appearanceCasesStatuses
     *
     * @return bool
     */
    public function validateAppearanceCasesStatuses(
        VybePublishRequest $vybePublishRequest,
        array $appearanceCasesStatuses
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function updateAppearanceCasesStatuses(
        VybePublishRequest $vybePublishRequest,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides getting data
     *
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
    ) : RequestStatusListItem;

    /**
     * This method provides deleting data
     *
     * @param VybePublishRequest $vybePublishRequest
     */
    public function deleteAllForPublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void;

    /**
     * This method provides getting data
     *
     * @param VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCaseStatus(
        VybePublishRequestAppearanceCase $vybePublishRequestAppearanceCase
    ) : RequestFieldStatusListItem;

    /**
     * This method provides getting data
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return RequestFieldStatusListItem
     */
    public function getAppearanceCasesStatus(
        VybePublishRequest $vybePublishRequest
    ) : RequestFieldStatusListItem;

    /**
     * This method provides getting data
     *
     * @param Collection|null $vybePublishRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybePublishRequests
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