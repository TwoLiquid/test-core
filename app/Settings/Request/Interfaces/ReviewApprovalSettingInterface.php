<?php

namespace App\Settings\Request\Interfaces;

/**
 * Interface ReviewApprovalSettingInterface
 *
 * @package App\Settings\Request\Interfaces
 */
interface ReviewApprovalSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getApprovalToPublishVybeReviews() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getApprovalToPublishBuyerReviews() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setApprovalToPublishVybeReviews(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setApprovalToPublishBuyerReviews(
        bool $value
    ) : void;
}