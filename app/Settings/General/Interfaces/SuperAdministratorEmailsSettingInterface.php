<?php

namespace App\Settings\General\Interfaces;

/**
 * Interface SuperAdministratorEmailsSettingInterface
 *
 * @package App\Settings\General\Interfaces
 */
interface SuperAdministratorEmailsSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return array|null
     */
    public function getEmails() : ?array;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param array $value
     */
    public function setEmails(
        array $value
    ) : void;
}