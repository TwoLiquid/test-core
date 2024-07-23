<?php

namespace App\Services\Vybe\Interfaces;

use App\Models\MongoDb\Vybe\VybeVersion;
use App\Models\MySql\Vybe\Vybe;

/**
 * Interface VybeVersionServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybeVersionServiceInterface
{
    /**
     * This method provides creating data
     *
     * @param Vybe $vybe
     *
     * @return VybeVersion
     */
    public function create(
        Vybe $vybe
    ) : VybeVersion;

    /**
     * This method provides getting data
     *
     * @param VybeVersion $vybeVersion
     *
     * @return VybeVersion|null
     */
    public function getPreviousVersion(
        VybeVersion $vybeVersion
    ) : ?VybeVersion;
}