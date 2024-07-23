<?php

namespace App\Settings\Vybe\Interfaces;

/**
 * Interface HandlingFeesSettingInterface
 *
 * @package App\Settings\Vybe\Interfaces
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
    public function getVybeSellerHandlingFee(
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
    public function getVybeTippingHandlingFee(
        bool $default
    ) : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int|null $value
     * @param bool|null $active
     */
    public function setVybeSellerHandlingFee(
        ?int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int|null $value
     * @param bool|null $active
     */
    public function setVybeTippingHandlingFee(
        ?int $value,
        ?bool $active
    ) : void;
}