<?php

namespace App\Settings\Request\Interfaces;

/**
 * Interface UserRequestsSettingInterface
 *
 * @package App\Settings\Request\Interfaces
 */
interface UserRequestsSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForProfileRequests() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForIdVerificationRequests() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForDeactivationRequests() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForUnsuspensionRequests() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForDeletionRequests() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForProfileRequests(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForIdVerificationRequests(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForDeactivationRequests(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForUnsuspensionRequests(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForDeletionRequests(
        bool $value
    ) : void;
}