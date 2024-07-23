<?php

namespace App\Settings\Aggregator;

use App\Models\MySql\User\User;
use App\Settings\Aggregator\Interfaces\UserSettingAggregatorInterface;
use App\Settings\User\AddFundsSetting;
use App\Settings\User\DaysThatVybesCanBeOrderedSetting;
use App\Settings\User\HandlingFeesSetting;
use App\Settings\User\MaximumNumberOfUsersSetting;
use App\Settings\User\MaximumNumberOfVybesSetting;
use App\Settings\User\PendingPayoutDelaySetting;
use App\Settings\User\PendingPayoutDelayTipSetting;
use App\Settings\User\RegistrationSetting;
use App\Settings\User\StreamerLabelSetting;
use App\Settings\User\TippingAmountSetting;
use App\Settings\User\VybesPricesSetting;
use App\Settings\User\WithdrawalSetting;
use Illuminate\Support\Str;

/**
 * Class UserSettingAggregator
 *
 * @package App\Settings\Aggregator
 */
class UserSettingAggregator implements UserSettingAggregatorInterface
{
    /**
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * Settings list
     *
     * @var array
     */
    protected array $settings = [];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return User|null
     */
    public function getUser() : ?User
    {
        return $this->user;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param User $user
     */
    public function setUser(
        User $user
    ) : void
    {
        $this->user = $user;
    }

    //--------------------------------------------------------------------------
    // Lists

    /**
     * @var array
     */
    protected array $buyerSettings = [
        RegistrationSetting::class,
        AddFundsSetting::class
    ];

    /**
     * @var array
     */
    protected array $sellerSettings = [
        HandlingFeesSetting::class,
        PendingPayoutDelaySetting::class,
        WithdrawalSetting::class,
        MaximumNumberOfVybesSetting::class,
        MaximumNumberOfUsersSetting::class,
        DaysThatVybesCanBeOrderedSetting::class,
        VybesPricesSetting::class,
        TippingAmountSetting::class,
        PendingPayoutDelayTipSetting::class,
        StreamerLabelSetting::class
    ];

    //--------------------------------------------------------------------------
    // Build

    /**
     * @param bool $customOnly
     *
     * @return array
     */
    public function getBuyer(
        bool $customOnly = false
    ) : array
    {
        return $this->build(
            $this->buyerSettings,
            $customOnly
        );
    }

    /**
     * @param bool $customOnly
     *
     * @return array
     */
    public function getSeller(
        bool $customOnly = false
    ) : array
    {
        return $this->build(
            $this->sellerSettings,
            $customOnly
        );
    }

    /**
     * Aggregator building method
     *
     * @param array $settings
     * @param bool $customOnly
     *
     * @return array
     */
    public function build(
        array $settings,
        bool $customOnly = false
    ) : array
    {
        foreach ($settings as $setting) {
            $settingBlock = new $setting();

            if ($this->user) {
                $settingBlock->setUser(
                    $this->user
                );
            }

            $this->settings[] = $settingBlock->build(
                $customOnly
            );
        }

        return array_values(
            array_filter(
                $this->settings
            )
        );
    }

    //--------------------------------------------------------------------------
    // Update

    /**
     * @param array $settingsItems
     */
    public function saveBuyer(
        array $settingsItems
    ) : void
    {
        $this->update(
            $this->buyerSettings,
            $settingsItems
        );
    }

    /**
     * @param array $settingsItems
     */
    public function saveSeller(
        array $settingsItems
    ) : void
    {
        $this->update(
            $this->sellerSettings,
            $settingsItems
        );
    }

    /**
     * @param array $settings
     * @param array $settingsItems
     */
    public function update(
        array $settings,
        array $settingsItems
    ) : void
    {
        foreach ($settings as $setting) {
            $settingBlock = new $setting();

            /** @var array $settingItem */
            foreach ($settingsItems as $settingItem) {
                if ($settingBlock->getCode() == $settingItem['code']) {

                    /** @var array $fieldItem */
                    foreach ($settingItem['fields'] as $fieldItem) {
                        $methodName = 'set' . ucfirst(Str::camel($fieldItem['code']));

                        if (method_exists($settingBlock, $methodName)) {
                            if ($this->user) {
                                $settingBlock->setUser(
                                    $this->user
                                );
                            }

                            $settingBlock->$methodName(
                                $fieldItem['value']
                            );
                        }
                    }
                }
            }
        }
    }
}