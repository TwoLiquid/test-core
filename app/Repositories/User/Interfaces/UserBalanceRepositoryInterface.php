<?php

namespace App\Repositories\User\Interfaces;

use App\Lists\User\Balance\Status\UserBalanceStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserBalance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserBalanceRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserBalanceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return UserBalance|null
     */
    public function findById(
        ?int $id
    ) : ?UserBalance;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows numbers
     * with an eloquent model
     *
     * @param array $ids
     *
     * @return UserBalance
     */
    public function getByIdsTypesCount(
        array $ids
    ) : UserBalance;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param UserBalanceStatusListItem $userBalanceStatusListItem
     *
     * @return UserBalance|null
     */
    public function store(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        UserBalanceStatusListItem $userBalanceStatusListItem
    ) : ?UserBalance;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     * @param User|null $user
     * @param UserBalanceTypeListItem|null $userBalanceTypeListItem
     * @param UserBalanceStatusListItem|null $userBalanceStatusListItem
     *
     * @return UserBalance
     */
    public function update(
        UserBalance $userBalance,
        ?User $user,
        ?UserBalanceTypeListItem $userBalanceTypeListItem,
        ?UserBalanceStatusListItem $userBalanceStatusListItem
    ) : UserBalance;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     * @param UserBalanceStatusListItem $userBalanceStatusListItem
     *
     * @return UserBalance
     */
    public function updateStatus(
        UserBalance $userBalance,
        UserBalanceStatusListItem $userBalanceStatusListItem
    ) : UserBalance;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     */
    public function increaseAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     */
    public function increasePendingAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     */
    public function decreaseAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     */
    public function decreasePendingAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param UserBalance $userBalance
     *
     * @return bool
     */
    public function delete(
        UserBalance $userBalance
    ) : bool;
}
