<?php

namespace App\Settings\Request\Interfaces;

/**
 * Interface FinanceRequestsSettingInterface
 *
 * @package App\Settings\Request\Interfaces
 */
interface FinanceRequestsSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForNewBillingChangeRequests() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForWithdrawalRequests() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getPermissionForPayoutMethodRequests() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForNewBillingChangeRequests(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForWithdrawalRequests(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setPermissionForPayoutMethodRequests(
        bool $value
    ) : void;
}