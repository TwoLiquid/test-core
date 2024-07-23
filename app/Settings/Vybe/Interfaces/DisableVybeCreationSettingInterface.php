<?php

namespace App\Settings\Vybe\Interfaces;

/**
 * Interface DisableVybeCreationSettingInterface
 *
 * @package App\Settings\Vybe\Interfaces
 */
interface DisableVybeCreationSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPreventUnverifiedUsers() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPreventAllUsers() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPreventUnverifiedUsers(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPreventAllUsers(
        bool $value
    ) : void;
}