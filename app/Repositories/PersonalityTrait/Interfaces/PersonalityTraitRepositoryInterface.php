<?php

namespace App\Repositories\PersonalityTrait\Interfaces;

use App\Lists\PersonalityTrait\PersonalityTraitListItem;
use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PersonalityTraitRepositoryInterface
 *
 * @package App\Repositories\PersonalityTrait\Interfaces
 */
interface PersonalityTraitRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PersonalityTrait|null
     */
    public function findById(
        ?int $id
    ) : ?PersonalityTrait;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param PersonalityTraitListItem $personalityTraitListItem
     * @param User $user
     *
     * @return PersonalityTrait|null
     */
    public function findForUser(
        PersonalityTraitListItem $personalityTraitListItem,
        User $user
    ) : ?PersonalityTrait;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getForUser(
        User $user
    ) : Collection;

    /**
     * This method provides checking row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param PersonalityTraitListItem $personalityTraitListItem
     *
     * @return bool
     */
    public function existsForUser(
        User $user,
        PersonalityTraitListItem $personalityTraitListItem
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param PersonalityTraitListItem $personalityTraitListItem
     *
     * @return PersonalityTrait|null
     */
    public function store(
        User $user,
        PersonalityTraitListItem $personalityTraitListItem
    ) : ?PersonalityTrait;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PersonalityTrait $personalityTrait
     *
     * @return bool
     */
    public function delete(
        PersonalityTrait $personalityTrait
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;
}
