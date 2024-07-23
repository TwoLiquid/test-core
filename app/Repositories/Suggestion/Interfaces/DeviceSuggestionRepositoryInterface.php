<?php

namespace App\Repositories\Suggestion\Interfaces;

use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DeviceSuggestionRepositoryInterface
 *
 * @package App\Repositories\Suggestion\Interfaces
 */
interface DeviceSuggestionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return DeviceSuggestion|null
     */
    public function findById(
        ?string $id
    ) : ?DeviceSuggestion;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return DeviceSuggestion|null
     */
    public function findForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : ?DeviceSuggestion;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return DeviceSuggestion|null
     */
    public function findForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : ?DeviceSuggestion;

    /**
     * This method provides getting data
     * with an eloquent model by certain query
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $vybeVersion
     * @param string|null $vybeTitle
     * @param string|null $deviceName
     *
     * @return Collection
     */
    public function getAllPending(
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $vybeVersion,
        ?string $vybeTitle,
        ?string $deviceName
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $vybeVersion
     * @param string|null $vybeTitle
     * @param string|null $deviceName
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPendingPaginated(
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $vybeVersion,
        ?string $vybeTitle,
        ?string $deviceName,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param VybePublishRequest|null $vybePublishRequest
     * @param VybeChangeRequest|null $vybeChangeRequest
     * @param Device|null $device
     * @param string|null $suggestion
     * @param bool|null $visible
     *
     * @return DeviceSuggestion|null
     */
    public function store(
        ?VybePublishRequest $vybePublishRequest,
        ?VybeChangeRequest $vybeChangeRequest,
        ?Device $device,
        ?string $suggestion,
        ?bool $visible
    ) : ?DeviceSuggestion;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param DeviceSuggestion $deviceSuggestion
     * @param Device|null $device
     * @param string|null $suggestion
     * @param bool|null $visible
     *
     * @return DeviceSuggestion
     */
    public function update(
        DeviceSuggestion $deviceSuggestion,
        ?Device $device,
        ?string $suggestion,
        ?bool $visible
    ) : DeviceSuggestion;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param DeviceSuggestion $deviceSuggestion
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return DeviceSuggestion
     */
    public function updateStatus(
        DeviceSuggestion $deviceSuggestion,
        RequestStatusListItem $requestStatusListItem
    ) : DeviceSuggestion;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param DeviceSuggestion $deviceSuggestion
     * @param Admin $admin
     *
     * @return DeviceSuggestion
     */
    public function updateAdmin(
        DeviceSuggestion $deviceSuggestion,
        Admin $admin
    ) : DeviceSuggestion;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return bool
     */
    public function delete(
        DeviceSuggestion $deviceSuggestion
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     */
    public function deletePendingForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool
     */
    public function deletePendingForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : bool;
}
