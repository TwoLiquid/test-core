<?php

namespace App\Settings\Base\Interfaces;

use App\Models\MySql\Vybe\Vybe;

/**
 * Interface BaseSettingInterface
 *
 * @package App\Settings\Base\Interfaces
 */
interface VybeSettingInterface
{
    /**
     * This method provides getting data
     *
     * @return Vybe|null
     */
    public function getVybe() : ?Vybe;

    /**
     * This method provides setting data
     *
     * @param Vybe $vybe
     */
    public function setVybe(
        Vybe $vybe
    ) : void;
}