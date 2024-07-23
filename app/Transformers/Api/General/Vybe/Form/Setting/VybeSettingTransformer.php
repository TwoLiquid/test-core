<?php

namespace App\Transformers\Api\General\Vybe\Form\Setting;

use App\Exceptions\DatabaseException;
use App\Settings\User\DaysThatVybesCanBeOrderedSetting;
use App\Settings\User\HandlingFeesSetting as UserHandlingFeesSetting;
use App\Settings\User\VybesPricesSetting;
use App\Settings\Vybe\DisableVybeCreationSetting;
use App\Settings\Vybe\DisableVybeInteractionTypesSetting;
use App\Settings\Vybe\HandlingFeesSetting as VybeHandlingFeesSetting;
use App\Settings\User\MaximumNumberOfUsersSetting;
use App\Transformers\BaseTransformer;

/**
 * Class VybeSettingTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Form\Setting
 */
class VybeSettingTransformer extends BaseTransformer
{
    /**
     * @var UserHandlingFeesSetting
     */
    protected UserHandlingFeesSetting $userDefaultHandlingFeesSetting;

    /**
     * @var VybeHandlingFeesSetting
     */
    protected VybeHandlingFeesSetting $vybeDefaultHandlingFeesSetting;

    /**
     * @var MaximumNumberOfUsersSetting
     */
    protected MaximumNumberOfUsersSetting $maximumNumberOfUsersDefaultSetting;

    /**
     * @var DaysThatVybesCanBeOrderedSetting
     */
    protected DaysThatVybesCanBeOrderedSetting $daysThatVybesCanBeOrderedDefaultSetting;

    /**
     * @var VybesPricesSetting
     */
    protected VybesPricesSetting $vybesPricesDefaultSetting;

    /**
     * @var DisableVybeCreationSetting
     */
    protected DisableVybeCreationSetting $disableVybeCreationDefaultSetting;

    /**
     * @var DisableVybeInteractionTypesSetting
     */
    protected DisableVybeInteractionTypesSetting $disableVybeInteractionTypesDefaultSetting;

    /**
     * VybeSettingTransformer constructor
     */
    public function __construct()
    {
        /** @var UserHandlingFeesSetting userDefaultHandlingFeesSetting */
        $this->userDefaultHandlingFeesSetting = new UserHandlingFeesSetting();

        /** @var VybeHandlingFeesSetting vybeDefaultHandlingFeesSetting */
        $this->vybeDefaultHandlingFeesSetting = new VybeHandlingFeesSetting();

        /** @var MaximumNumberOfUsersSetting maximumNumberOfUsersDefaultSetting */
        $this->maximumNumberOfUsersDefaultSetting = new MaximumNumberOfUsersSetting();

        /** @var DaysThatVybesCanBeOrderedSetting daysThatVybesCanBeOrderedDefaultSetting */
        $this->daysThatVybesCanBeOrderedDefaultSetting = new DaysThatVybesCanBeOrderedSetting();

        /** @var VybesPricesSetting vybesPricesDefaultSetting */
        $this->vybesPricesDefaultSetting = new VybesPricesSetting();

        /** @var DisableVybeCreationSetting disableVybeCreationDefaultSetting */
        $this->disableVybeCreationDefaultSetting = new DisableVybeCreationSetting();

        /** @var DisableVybeInteractionTypesSetting disableVybeInteractionTypesDefaultSetting */
        $this->disableVybeInteractionTypesDefaultSetting = new DisableVybeInteractionTypesSetting();
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform() : array
    {
        return [
            'default' => [
                'handling_fees' => [
                    'seller_handling_fee'  => $this->userDefaultHandlingFeesSetting->getSellerHandlingFee(),
                    'tipping_handling_fee' => $this->userDefaultHandlingFeesSetting->getTippingHandlingFee()
                ],
                'maximum_number_of_users' => [
                    'group_vybes' => $this->maximumNumberOfUsersDefaultSetting->getGroupVybes(),
                    'events'      => $this->maximumNumberOfUsersDefaultSetting->getEvents()
                ],
                'days_that_vybes_can_be_ordered' => [
                    'solo_vybes.minimum_days'  => $this->daysThatVybesCanBeOrderedDefaultSetting->getSoloVybesMinimumDays(),
                    'solo_vybes.maximum_days'  => $this->daysThatVybesCanBeOrderedDefaultSetting->getSoloVybesMaximumDays(),
                    'group_vybes.minimum_days' => $this->daysThatVybesCanBeOrderedDefaultSetting->getGroupVybesMinimumDays(),
                    'group_vybes.maximum_days' => $this->daysThatVybesCanBeOrderedDefaultSetting->getGroupVybesMaximumDays()
                ],
                'vybe_prices' => [
                    'voice_chat.minimum' => $this->vybesPricesDefaultSetting->getVoiceChatMinimum(),
                    'voice_chat.maximum' => $this->vybesPricesDefaultSetting->getVoiceChatMaximum(),
                    'video_chat.minimum' => $this->vybesPricesDefaultSetting->getVideoChatMinimum(),
                    'video_chat.maximum' => $this->vybesPricesDefaultSetting->getVideoChatMaximum(),
                    'real_life.minimum'  => $this->vybesPricesDefaultSetting->getRealLifeMinimum(),
                    'real_life.maximum'  => $this->vybesPricesDefaultSetting->getRealLifeMaximum()
                ],
                'disable_vybe_creation' => [
                    'prevent_unverified_users' => $this->disableVybeCreationDefaultSetting->getPreventUnverifiedUsers(),
                    'prevent_all_users'        => $this->disableVybeCreationDefaultSetting->getPreventAllUsers()
                ],
                'disable_interaction_types' => [
                    'voice_chat' => $this->disableVybeInteractionTypesDefaultSetting->getVoiceChat(),
                    'video_chat' => $this->disableVybeInteractionTypesDefaultSetting->getVideoChat(),
                    'real_life'  => $this->disableVybeInteractionTypesDefaultSetting->getRealLife()
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'settings';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'settings';
    }
}
