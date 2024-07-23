<?php

namespace App\Services\Admin\Interfaces;

/**
 * Interface AdminNavbarServiceInterface
 *
 * @package App\Services\Admin\Interfaces
 */
interface AdminNavbarServiceInterface
{
    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getUsers() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getRequests() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getOrders() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getInvoices() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getReviews() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getChat() : array;

    /**
     * This method provides getting data
     *
     * @return array
     */
    public function getSupport() : array;

    /**
     * This method provides updating data
     */
    public function releaseAllUserCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseAllWithdrawalCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseAllOrderCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseAllTipCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseAddFundsCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseProfileRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseIdVerificationRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseUserDeactivationRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseUserUnsuspensionRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseUserDeletionRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseVybePublishRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseVybeChangeRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseVybeUnpublishRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseVybeUnsuspendRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseVybeDeletionRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releaseBillingChangeRequestCache() : void;

    /**
     * This method provides updating data
     */
    public function releasePayoutMethodRequestCache() : void;
}