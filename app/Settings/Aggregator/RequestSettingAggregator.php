<?php

namespace App\Settings\Aggregator;

use App\Settings\Aggregator\Interfaces\RequestSettingAggregatorInterface;
use App\Settings\Request\FinanceRequestsSetting;
use App\Settings\Request\ReviewApprovalSetting;
use App\Settings\Request\UserRequestsSetting;
use App\Settings\Request\VybeRequestsSetting;
use Illuminate\Support\Str;

/**
 * Class RequestSettingAggregator
 * 
 * @package App\Settings\Aggregator
 */
class RequestSettingAggregator extends BaseSettingAggregator implements RequestSettingAggregatorInterface
{
    /**
     * Settings list
     *
     * @var array
     */
    protected array $settings = [];

    //--------------------------------------------------------------------------
    // Lists

    /**
     * @var array
     */
    protected array $adminMainSettings = [
        UserRequestsSetting::class,
        VybeRequestsSetting::class,
        FinanceRequestsSetting::class,
        ReviewApprovalSetting::class
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