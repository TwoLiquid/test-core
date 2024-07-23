<?php

namespace App\Settings\Vybe;

use App\Exceptions\DatabaseException;
use App\Settings\Base\VybeSetting;
use App\Settings\Vybe\Interfaces\HandlingFeesSettingInterface;

/**
 * Class HandlingFeesSetting
 *
 * @package App\Settings\Vybe
 */
class HandlingFeesSetting extends VybeSetting implements HandlingFeesSettingInterface
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
    protected array $vybeSellerHandlingFee = [
        'code'           => 'vybe_handling_fee',
        'name'           => 'Vybe handling fee',
        'type'           => 'integer',
        'icon'           => 'percent',
        'custom'         => true,
        'original_value' => 10
    ];

    /**
     * @var array
     */
    protected array $vybeTippingHandlingFee = [
        'code'           => 'vybe_tipping_handling_fee',
        'name'           => 'Vybe tipping handling fee',
        'type'           => 'integer',
        'icon'           => 'percent',
        'custom'         => true,
        'original_value' => 10
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
    public function getVybeSellerHandlingFee(
        bool $default = true
    ) : ?int
    {
        /**
         * Checking initialized user existence
         */
        if ($this->vybe) {

            /**
             * Getting custom vybe setting
             */
            $vybeSetting = $this->vybeSettingRepository->findByCodeCustom(
                $this->vybe,
                $this->code,
                $this->vybeSellerHandlingFee['code']
            );

            /**
             * Checking custom vybe setting existence
             */
            if ($vybeSetting) {
                return $vybeSetting->value ? (int) $vybeSetting->value : null;
            }
        }

        if ($default) {

            /**
             * Getting default vybe setting
             */
            $vybeSetting = $this->vybeSettingRepository->findByCodeDefault(
                $this->code,
                $this->vybeSellerHandlingFee['code']
            );

            /**
             * Checking default user setting existence
             */
            if ($vybeSetting) {
                return $vybeSetting->value ? (int) $vybeSetting->value : null;
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
    public function getVybeTippingHandlingFee(
        bool $default = true
    ) : ?int
    {
        /**
         * Checking initialized vybe existence
         */
        if ($this->vybe) {

            /**
             * Getting custom vybe setting
             */
            $vybeSetting = $this->vybeSettingRepository->findByCodeCustom(
                $this->vybe,
                $this->code,
                $this->vybeTippingHandlingFee['code']
            );

            /**
             * Checking custom vybe setting existence
             */
            if ($vybeSetting) {
                return $vybeSetting->value ? (int) $vybeSetting->value : null;
            }
        }

        if ($default) {

            /**
             * Getting default vybe setting
             */
            $vybeSetting = $this->vybeSettingRepository->findByCodeDefault(
                $this->code,
                $this->vybeTippingHandlingFee['code']
            );

            /**
             * Checking default vybe setting existence
             */
            if ($vybeSetting) {
                return $vybeSetting->value ? (int) $vybeSetting->value : null;
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
    public function setVybeSellerHandlingFee(
        ?int $value,
        ?bool $active = null
    ) : void
    {
        /**
         * Checking initialized vybe existence
         */
        if ($this->vybe) {

            /**
             * Updating vybe setting
             */
            $this->vybeSettingRepository->updateOrCreateValueByCodeCustom(
                $this->vybe,
                $this->code,
                $this->vybeSellerHandlingFee['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating vybe setting
             */
            $this->vybeSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->vybeSellerHandlingFee['code'],
                (int) $value
            );
        }
    }

    /**
     * @param int|null $value
     * @param bool|null $active
     *
     * @throws DatabaseException
     */
    public function setVybeTippingHandlingFee(
        ?int $value,
        ?bool $active = null
    ) : void
    {
        /**
         * Checking initialized vybe existence
         */
        if ($this->vybe) {

            /**
             * Updating vybe setting
             */
            $this->vybeSettingRepository->updateOrCreateValueByCodeCustom(
                $this->vybe,
                $this->code,
                $this->vybeTippingHandlingFee['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating vybe setting
             */
            $this->vybeSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->vybeTippingHandlingFee['code'],
                (int) $value
            );
        }
    }

    //--------------------------------------------------------------------------
    // Build

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function build() : array
    {
        return [
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => array_filter([
                $this->processField(
                    false,
                    $this->vybeSellerHandlingFee,
                    $this->getVybeSellerHandlingFee()
                ),
                $this->processField(
                    false,
                    $this->vybeTippingHandlingFee,
                    $this->getVybeTippingHandlingFee()
                )
            ])
        ];
    }

    //--------------------------------------------------------------------------
    // Seed

    /**
     * Empty seeder
     */
    public function seed() : void {}
}