<?php

namespace App\Repositories\Platform\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Platform;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PlatformRepositoryInterface
 *
 * @package App\Repositories\Platform\Interfaces
 */
interface PlatformRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Platform|null
     */
    public function findById(
        ?int $id
    ) : ?Platform;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Platform|null
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?Platform;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array|null $platformsIds
     *
     * @return Collection
     */
    public function getByIds(
        ?array $platformsIds
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param Activity $activity
     * @param bool|null $voiceChat
     * @param bool|null $videoChat
     *
     * @return Collection
     */
    public function getByActivity(
        Activity $activity,
        ?bool $voiceChat,
        ?bool $videoChat
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @return Collection
     */
    public function getAllForVoiceChat() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @return Collection
     */
    public function getAllForVideoChat() : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $name
     * @param bool $voiceChat
     * @param bool $visibleInVoiceChat
     * @param bool $videoChat
     * @param bool $visibleInVideoChat
     *
     * @return Platform|null
     */
    public function store(
        string $name,
        bool $voiceChat,
        bool $visibleInVoiceChat,
        bool $videoChat,
        bool $visibleInVideoChat
    ) : ?Platform;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Platform $platform
     * @param string|null $name
     * @param bool|null $voiceChat
     * @param bool|null $visibleInVoiceChat
     * @param bool|null $videoChat
     * @param bool|null $visibleInVideoChat
     *
     * @return Platform
     */
    public function update(
        Platform $platform,
        ?string $name,
        ?bool $voiceChat,
        ?bool $visibleInVoiceChat,
        ?bool $videoChat,
        ?bool $visibleInVideoChat
    ) : Platform;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param Activity $activity
     */
    public function attachActivity(
        Platform $platform,
        Activity $activity
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param array $activitiesIds
     * @param bool|null $detaching
     */
    public function attachActivities(
        Platform $platform,
        array $activitiesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param Activity $activity
     */
    public function detachActivity(
        Platform $platform,
        Activity $activity
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param array $activitiesIds
     */
    public function detachActivities(
        Platform $platform,
        array $activitiesIds
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param AppearanceCase $appearanceCase
     */
    public function attachAppearanceCase(
        Platform $platform,
        AppearanceCase $appearanceCase
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param array $appearanceCasesIds
     * @param bool|null $detaching
     */
    public function attachAppearanceCases(
        Platform $platform,
        array $appearanceCasesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param AppearanceCase $appearanceCase
     */
    public function detachAppearanceCase(
        Platform $platform,
        AppearanceCase $appearanceCase
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Platform $platform
     * @param array $appearanceCasesIds
     */
    public function detachAppearanceCases(
        Platform $platform,
        array $appearanceCasesIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Platform $platform
     *
     * @return bool
     */
    public function delete(
        Platform $platform
    ) : bool;
}
