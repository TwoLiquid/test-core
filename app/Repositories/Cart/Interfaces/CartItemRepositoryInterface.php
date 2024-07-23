<?php

namespace App\Repositories\Cart\Interfaces;

use App\Models\MySql\AppearanceCase;
use App\Models\MySql\CartItem;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface CartItemRepositoryInterface
 *
 * @package App\Repositories\Cart\Interfaces
 */
interface CartItemRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return CartItem|null
     */
    public function findById(
        ?int $id
    ) : ?CartItem;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return CartItem|null
     */
    public function findFullById(
        ?int $id
    ) : ?CartItem;

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
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getByUser(
        User $user
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param AppearanceCase $appearanceCase
     * @param Timeslot|null $timeslot
     * @param string $datetimeFrom
     * @param string $datetimeTo
     * @param int $quantity
     *
     * @return CartItem|null
     */
    public function store(
        User $user,
        AppearanceCase $appearanceCase,
        ?Timeslot $timeslot,
        string $datetimeFrom,
        string $datetimeTo,
        int $quantity
    ) : ?CartItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CartItem $cartItem
     * @param User|null $user
     * @param AppearanceCase|null $appearanceCase
     * @param Timeslot|null $timeslot
     * @param string|null $datetimeFrom
     * @param string|null $datetimeTo
     * @param int|null $quantity
     *
     * @return CartItem
     */
    public function update(
        CartItem $cartItem,
        ?User $user,
        ?AppearanceCase $appearanceCase,
        ?Timeslot $timeslot,
        ?string $datetimeFrom,
        ?string $datetimeTo,
        ?int $quantity
    ) : CartItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CartItem $cartItem
     * @param int $quantity
     *
     * @return CartItem
     */
    public function updateQuantity(
        CartItem $cartItem,
        int $quantity
    ) : CartItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CartItem $cartItem
     * @param Timeslot $timeslot
     *
     * @return CartItem
     */
    public function updateTimeslot(
        CartItem $cartItem,
        Timeslot $timeslot
    ) : CartItem;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param CartItem $cartItem
     *
     * @return bool
     */
    public function delete(
        CartItem $cartItem
    ) : bool;
}
