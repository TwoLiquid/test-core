<?php

namespace App\Services\User\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserUnsuspendRequestServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserUnsuspendRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $userUnsuspendRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $userUnsuspendRequests
    ) : Collection;
}