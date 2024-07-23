<?php

namespace App\Services\User\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserDeletionRequestServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserDeletionRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $userDeletionRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $userDeletionRequests
    ) : Collection;
}