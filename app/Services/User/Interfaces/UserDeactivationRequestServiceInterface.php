<?php

namespace App\Services\User\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserDeactivationRequestServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserDeactivationRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $userDeactivationRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $userDeactivationRequests
    ) : Collection;
}