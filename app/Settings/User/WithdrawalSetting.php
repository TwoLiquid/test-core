<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\WithdrawalSettingInterface;

/**
 * Class WithdrawalSetting
 *
 * @package App\Settings\User
 */
class WithdrawalSetting extends UserSetting implements WithdrawalSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Withdrawal settings';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'withdrawal_settings';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $minimumWithdrawal = [
        'code'           => 'minimum_withdrawal',
        'name'           => 'Minimum withdrawal',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 100
    ];

    /**
     * @var array
     */
    protected array $maximumWithdrawal = [
        'code'           => 'maximum_withdrawal',
        'name'           => 'Maximum withdrawal',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 500
    ];

    /**
     * @var array
     */
    protected array $disableWithdrawalsNoCustom = [
        'code'           => 'disable_withdrawals_no_custom',
        'name'           => 'Disable withdrawals for all sellers with no custom withdrawal rates',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => false
    ];

    /**
     * @var array
     */
    protected array $disableWithdrawalsForAll = [
        'code'           => 'disable_withdrawals_for_all_sellers',
        'name'           => 'Disable withdrawals for all sellers',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $autoWithdrawalWithoutApproval = [
        'code'           => 'auto_withdrawal_without_approval',
        'name'           => 'Auto withdrawal to buyer balance without approval for sellers',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => true
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getMinimumWithdrawal() : ?int
    {
        /**
         * Checking initialized user existence
         */
        if ($this->user) {

            /**
             * Getting custom user setting
             */
            $userSetting = $this->userSettingRepository->findByCodeCustom(
                $this->user,
                $this->code,
                $this->minimumWithdrawal['code']
            );

            /**
             * Checking custom user setting existence
             */
            if ($userSetting) {
                return (int) $userSetting->value;
            }
        }

        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->minimumWithdrawal['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
        }

        return null;
    }

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getMaximumWithdrawal() : ?int
    {
        /**
         * Checking initialized user existence
         */
        if ($this->user) {

            /**
             * Getting custom user setting
             */
            $userSetting = $this->userSettingRepository->findByCodeCustom(
                $this->user,
                $this->code,
                $this->maximumWithdrawal['code']
            );

            /**
             * Checking custom user setting existence
             */
            if ($userSetting) {
                return (int) $userSetting->value;
            }
        }

        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->maximumWithdrawal['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getDisableWithdrawalsNoCustom() : ?bool
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->disableWithdrawalsNoCustom['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (bool) $userSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getDisableWithdrawalsForAll() : ?bool
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->disableWithdrawalsForAll['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (bool) $userSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getAutoWithdrawalWithoutApproval() : ?bool
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->autoWithdrawalWithoutApproval['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (bool) $userSetting->value;
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param int $value
     * @param bool|null $active
     *
     * @throws DatabaseException
     */
    public function setMinimumWithdrawal(
        int $value,
        ?bool $active = null
    ) : void
    {
        /**
         * Checking initialized user existence
         */
        if ($this->user) {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateOrCreateValueByCodeCustom(
                $this->user,
                $this->code,
                $this->minimumWithdrawal['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->minimumWithdrawal['code'],
                $value
            );
        }
    }

    /**
     * @param int $value
     * @param bool|null $active
     *
     * @throws DatabaseException
     */
    public function setMaximumWithdrawal(
        int $value,
        ?bool $active = null
    ) : void
    {
        /**
         * Checking initialized user existence
         */
        if ($this->user) {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateOrCreateValueByCodeCustom(
                $this->user,
                $this->code,
                $this->maximumWithdrawal['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->maximumWithdrawal['code'],
                $value
            );
        }
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setDisableWithdrawalsNoCustom(
        bool $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->disableWithdrawalsNoCustom['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setDisableWithdrawalsForAll(
        bool $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->disableWithdrawalsForAll['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setAutoWithdrawalWithoutApproval(
        bool $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->autoWithdrawalWithoutApproval['code'],
            $value
        );
    }

    //--------------------------------------------------------------------------
    // Build

    /**
     * @param bool|null $customOnly
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function build(
        ?bool $customOnly = false
    ) : array
    {
        return [
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => array_filter([
                $this->processField(
                    $customOnly,
                    $this->minimumWithdrawal,
                    $this->getMinimumWithdrawal()
                ),
                $this->processField(
                    $customOnly,
                    $this->maximumWithdrawal,
                    $this->getMaximumWithdrawal()
                ),
                $this->processField(
                    $customOnly,
                    $this->disableWithdrawalsNoCustom,
                    $this->getDisableWithdrawalsNoCustom()
                ),
                $this->processField(
                    $customOnly,
                    $this->disableWithdrawalsForAll,
                    $this->getDisableWithdrawalsForAll()
                ),
                $this->processField(
                    $customOnly,
                    $this->autoWithdrawalWithoutApproval,
                    $this->getAutoWithdrawalWithoutApproval()
                )
            ])
        ];
    }

    //--------------------------------------------------------------------------
    // Seed

    /**
     * @throws DatabaseException
     */
    public function seed() : void
    {
        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->minimumWithdrawal['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->minimumWithdrawal['code'],
                $this->minimumWithdrawal['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->maximumWithdrawal['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->maximumWithdrawal['code'],
                $this->maximumWithdrawal['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->disableWithdrawalsNoCustom['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->disableWithdrawalsNoCustom['code'],
                $this->disableWithdrawalsNoCustom['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->disableWithdrawalsForAll['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->disableWithdrawalsForAll['code'],
                $this->disableWithdrawalsForAll['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->autoWithdrawalWithoutApproval['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->autoWithdrawalWithoutApproval['code'],
                $this->autoWithdrawalWithoutApproval['original_value'],
                true
            );
        }
    }
}