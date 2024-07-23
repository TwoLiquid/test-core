<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface AddFundsSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface AddFundsSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getMinimumAmount() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getMaximumAmount() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getDisableAddingFundsNoCustom() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getDisableAddingFundsForAll() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setMinimumAmount(
        int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setMaximumAmount(
        int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setDisableAddingFundsNoCustom(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setDisableAddingFundsForAll(
        bool $value
    ) : void;
}