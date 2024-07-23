<?php

namespace App\Services\Cart\Interfaces;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CartServiceInterface
 *
 * @package App\Services\Cart\Interfaces
 */
interface CartServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getRefreshedItems(
        User $user
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param array $cartItems
     *
     * @return Collection
     */
    public function refresh(
        User $user,
        array $cartItems
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param User $user
     * @param array $cartItems
     *
     * @return bool
     */
    public function checkTimeslotAvailability(
        User $user,
        array $cartItems
    ) : bool;
}
