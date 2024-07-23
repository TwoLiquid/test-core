<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\AddFundsSettingInterface;

/**
 * Class AddFundsSetting
 *
 * @package App\Settings\User
 */
class AddFundsSetting extends UserSetting implements AddFundsSettingInterface
{
    //--------------------------------------------------------------------------
    // Setting data

    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Add funds settings';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'add_funds_settings';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $minimumAmount = [
        'code'           => 'minimum_amount',
        'name'           => 'Minimum amount',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 15
    ];

    /**
     * @var array
     */
    protected array $maximumAmount = [
        'code'           => 'maximum_amount',
        'name'           => 'Maximum amount',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 500
    ];

    /**
     * @var array
     */
    protected array $disableAddingFundsNoCustom = [
        'code'           => 'disable_adding_funds_no_custom',
        'name'           => 'Disable adding funds for all users with no custom Add funds rates',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => false
    ];

    /**
     * @var array
     */
    protected array $disableAddingFundsForAll = [
        'code'           => 'disable_adding_funds_for_all',
        'name'           => 'Disable adding funds for all users',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => false
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getMinimumAmount() : ?int
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
                $this->minimumAmount['code']
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
            $this->minimumAmount['code']
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
    public function getMaximumAmount() : ?int
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
                $this->maximumAmount['code']
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
            $this->maximumAmount['code']
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
    public function getDisableAddingFundsNoCustom() : ?bool
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->disableAddingFundsNoCustom['code']
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
    public function getDisableAddingFundsForAll() : ?bool
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->disableAddingFundsForAll['code']
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
    public function setMinimumAmount(
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
                $this->minimumAmount['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->minimumAmount['code'],
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
    public function setMaximumAmount(
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
                $this->maximumAmount['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->maximumAmount['code'],
                $value
            );
        }
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setDisableAddingFundsNoCustom(
        bool $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->disableAddingFundsNoCustom['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setDisableAddingFundsForAll(
        bool $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->disableAddingFundsForAll['code'],
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
                    $this->minimumAmount,
                    $this->getMinimumAmount()
                ),
                $this->processField(
                    $customOnly,
                    $this->maximumAmount,
                    $this->getMaximumAmount()
                ),
                $this->processField(
                    $customOnly,
                    $this->disableAddingFundsNoCustom,
                    $this->getDisableAddingFundsNoCustom()
                ),
                $this->processField(
                    $customOnly,
                    $this->disableAddingFundsForAll,
                    $this->getDisableAddingFundsForAll()
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
            $this->minimumAmount['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->minimumAmount['code'],
                $this->minimumAmount['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->maximumAmount['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->maximumAmount['code'],
                $this->maximumAmount['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->disableAddingFundsNoCustom['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->disableAddingFundsNoCustom['code'],
                $this->disableAddingFundsNoCustom['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->disableAddingFundsForAll['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->disableAddingFundsForAll['code'],
                $this->disableAddingFundsForAll['original_value'],
                true
            );
        }
    }
}