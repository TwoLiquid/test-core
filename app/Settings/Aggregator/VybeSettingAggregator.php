<?php

namespace App\Settings\Aggregator;

use App\Models\MySql\Vybe\Vybe;
use App\Settings\Aggregator\Interfaces\VybeSettingAggregatorInterface;
use App\Settings\Vybe\DisableVybeCreationSetting;
use App\Settings\Vybe\DisableVybeInteractionTypesSetting;
use App\Settings\Vybe\HandlingFeesSetting;
use App\Settings\Vybe\PendingHoursFromOrderFinishedSetting;
use Illuminate\Support\Str;

/**
 * Class VybeSettingAggregator
 *
 * @package App\Settings\Aggregator
 */
class VybeSettingAggregator extends BaseSettingAggregator implements VybeSettingAggregatorInterface
{
    /**
     * @var Vybe|null
     */
    protected ?Vybe $vybe = null;

    /**
     * Settings list
     *
     * @var array
     */
    protected array $settings = [];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return Vybe|null
     */
    public function getVybe() : ?Vybe
    {
        return $this->vybe;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param Vybe $vybe
     */
    public function setVybe(
        Vybe $vybe
    ) : void
    {
        $this->vybe = $vybe;
    }

    //--------------------------------------------------------------------------
    // Lists

    /**
     * @var array
     */
    protected array $adminMainSettings = [
        DisableVybeCreationSetting::class,
        DisableVybeInteractionTypesSetting::class,
        PendingHoursFromOrderFinishedSetting::class
    ];

    /**
     * @var array
     */
    protected array $adminVybePageSettings = [
        HandlingFeesSetting::class
    ];

    //--------------------------------------------------------------------------
    // Build

    /**
     * @return array
     */
    public function getAdminMain() : array
    {
        return $this->build(
            $this->adminMainSettings
        );
    }

    /**
     * @return array
     */
    public function getAdminVybePage() : array
    {
        return $this->build(
            $this->adminVybePageSettings
        );
    }

    /**
     * Aggregator building method
     *
     * @param array $settings
     *
     * @return array
     */
    public function build(
        array $settings
    ) : array
    {
        foreach ($settings as $setting) {
            $settingBlock = new $setting();

            if ($this->vybe) {
                $settingBlock->setVybe(
                    $this->vybe
                );
            }

            $this->settings[] = $settingBlock->build();
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
    public function saveAdminMain(
        array $settingsItems
    ) : void
    {
        $this->update(
            $this->adminMainSettings,
            $settingsItems
        );
    }

    /**
     * @param array $settingsItems
     */
    public function saveAdminVybePage(
        array $settingsItems
    ) : void
    {
        $this->update(
            $this->adminVybePageSettings,
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
                            if ($this->vybe) {
                                $settingBlock->setVybe(
                                    $this->vybe
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