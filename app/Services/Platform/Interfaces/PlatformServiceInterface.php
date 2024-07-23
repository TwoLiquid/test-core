<?php

namespace App\Services\Platform\Interfaces;

use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PlatformServiceInterface
 *
 * @package App\Services\Platform\Interfaces
 */
interface PlatformServiceInterface
{
    /**
     * This method provides updating data
     *
     * @param array $platformsItems
     *
     * @return Collection
     */
    public function updatePositions(
        array $platformsItems
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     *
     * @return Collection
     */
    public function getByVybe(
        Vybe $vybe
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $appearanceCases
     *
     * @return Collection
     */
    public function getByAppearanceCases(
        Collection $appearanceCases
    ) : Collection;
}
