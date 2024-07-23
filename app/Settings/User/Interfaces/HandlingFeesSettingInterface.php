<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface HandlingFeesSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface HandlingFeesSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @param bool $default
     *
     * @return int|null
     */
    public function getSellerHandlingFee(
        bool $default
    ) : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @param bool $default
     *
     * @return int|null
     */
    public function getTippingHandlingFee(
        bool $default
    ) : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int|null $value
     * @param bool|null $active
     */
    public function setSellerHandlingFee(
        ?int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setTippingHandlingFee(
        int $value,
        ?bool $active
    ) : void;
}