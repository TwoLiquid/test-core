<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\HandlingFeesSettingInterface;

/**
 * Class HandlingFeesSetting
 *
 * @package App\Settings\User
 */
class HandlingFeesSetting extends UserSetting implements HandlingFeesSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Handling fees';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'handling_fees';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $sellerHandlingFee = [
        'code'           => 'seller_handling_fee',
        'name'           => 'Seller handling fee',
        'type'           => 'integer',
        'icon'           => 'percent',
        'custom'         => true,
        'original_value' => 22
    ];

    /**
     * @var array
     */
    protected array $tippingHandlingFee = [
        'code'           => 'tipping_handling_fee',
        'name'           => 'Tipping handling fee',
        'type'           => 'integer',
        'icon'           => 'percent',
        'custom'         => true,
        'original_value' => 22
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @param bool $default
     *
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getSellerHandlingFee(
        bool $default = true
    ) : ?int
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
                $this->sellerHandlingFee['code']
            );

            /**
             * Checking custom user setting existence
             */
            if ($userSetting) {
                return $userSetting->value ? (int) $userSetting->value : null;
            }
        }

        if ($default) {

            /**
             * Getting default user setting
             */
            $userSetting = $this->userSettingRepository->findByCodeDefault(
                $this->code,
                $this->sellerHandlingFee['code']
            );

            /**
             * Checking default user setting existence
             */
            if ($userSetting) {
                return $userSetting->value ? (int) $userSetting->value : null;
            }
        }

        return null;
    }

    /**
     * @param bool $default
     *
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getTippingHandlingFee(
        bool $default = true
    ) : ?int
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
                $this->tippingHandlingFee['code']
            );

            /**
             * Checking custom user setting existence
             */
            if ($userSetting) {
                return $userSetting->value ? (int) $userSetting->value : null;
            }
        }

        if ($default) {

            /**
             * Getting default user setting
             */
            $userSetting = $this->userSettingRepository->findByCodeDefault(
                $this->code,
                $this->tippingHandlingFee['code']
            );

            /**
             * Checking default user setting existence
             */
            if ($userSetting) {
                return $userSetting->value ? (int) $userSetting->value : null;
            }
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param int|null $value
     * @param bool|null $active
     *
     * @throws DatabaseException
     */
    public function setSellerHandlingFee(
        ?int $value,
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
                $this->sellerHandlingFee['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->sellerHandlingFee['code'],
                (int) $value
            );
        }
    }

    /**
     * @param int $value
     * @param bool|null $active
     *
     * @throws DatabaseException
     */
    public function setTippingHandlingFee(
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
                $this->tippingHandlingFee['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->tippingHandlingFee['code'],
                $value
            );
        }
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
                    $this->sellerHandlingFee,
                    $this->getSellerHandlingFee()
                ),
                $this->processField(
                    $customOnly,
                    $this->tippingHandlingFee,
                    $this->getTippingHandlingFee()
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
            $this->sellerHandlingFee['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->sellerHandlingFee['code'],
                $this->sellerHandlingFee['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->tippingHandlingFee['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->tippingHandlingFee['code'],
                $this->tippingHandlingFee['original_value'],
                true
            );
        }
    }
}