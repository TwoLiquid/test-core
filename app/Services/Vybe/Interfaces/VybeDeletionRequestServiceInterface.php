<?php

namespace App\Services\Vybe\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeDeletionRequestServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybeDeletionRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $vybeDeletionRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeDeletionRequests
    ) : Collection;
}