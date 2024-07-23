<?php

namespace App\Services\Vybe\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeUnsuspendRequestServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybeUnsuspendRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $vybeUnsuspendRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeUnsuspendRequests
    ) : Collection;
}