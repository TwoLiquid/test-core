<?php

namespace App\Services\Suggestion\Interfaces;

use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CsauSuggestionServiceInterface
 *
 * @package App\Services\Suggestion\Interfaces
 */
interface CsauSuggestionServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     */
    public function executeForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection;

    /**
     * This method provides creating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     */
    public function executeForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     */
    public function isFullyAccepted(
        CsauSuggestion $csauSuggestion
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     */
    public function isPartlyDeclined(
        CsauSuggestion $csauSuggestion
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param VybePublishRequest $vybePublishRequest
     */
    public function processForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void;

    /**
     * This method provides updating data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     */
    public function processForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void;

    /**
     * This method provides getting data
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     */
    public function getAllVybePublishRequestPlatforms(
        VybePublishRequest $vybePublishRequest
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     */
    public function getAllVybeChangeRequestPlatforms(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection;
}