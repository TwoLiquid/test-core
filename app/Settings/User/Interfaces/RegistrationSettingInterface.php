<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface RegistrationSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface RegistrationSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getDisableRegistration() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setDisableRegistration(
        bool $value
    ) : void;
}