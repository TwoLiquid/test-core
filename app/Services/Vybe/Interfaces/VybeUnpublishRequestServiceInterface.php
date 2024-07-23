<?php

namespace App\Services\Vybe\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeUnpublishRequestServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybeUnpublishRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $vybeUnpublishRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $vybeUnpublishRequests
    ) : Collection;
}