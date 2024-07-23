<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface WithdrawalSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface WithdrawalSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getMinimumWithdrawal() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getMaximumWithdrawal() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getDisableWithdrawalsNoCustom() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getDisableWithdrawalsForAll() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getAutoWithdrawalWithoutApproval() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setMinimumWithdrawal(
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
    public function setMaximumWithdrawal(
        int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setDisableWithdrawalsNoCustom(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setDisableWithdrawalsForAll(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setAutoWithdrawalWithoutApproval(
        bool $value
    ) : void;
}