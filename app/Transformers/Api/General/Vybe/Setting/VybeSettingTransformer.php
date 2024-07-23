<?php

namespace App\Transformers\Api\General\Vybe\Setting;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
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
 * @package App\Transformers\Api\General\Vybe\Setting
 */
class VybeSettingTransformer extends BaseTransformer
{
    /**
     * @var Vybe|null
     */
    protected ?Vybe $vybe;

    /**
     * @var UserHandlingFeesSetting
     */
    protected UserHandlingFeesSetting $userDefaultHandlingFeesSetting;

    /**
     * @var UserHandlingFeesSetting
     */
    protected UserHandlingFeesSetting $userCustomHandlingFeesSetting;

    /**
     * @var VybeHandlingFeesSetting
     */
    protected VybeHandlingFeesSetting $vybeDefaultHandlingFeesSetting;

    /**
     * @var VybeHandlingFeesSetting
     */
    protected VybeHandlingFeesSetting $vybeCustomHandlingFeesSetting;

    /**
     * @var MaximumNumberOfUsersSetting
     */
    protected MaximumNumberOfUsersSetting $maximumNumberOfUsersDefaultSetting;

    /**
     * @var MaximumNumberOfUsersSetting
     */
    protected MaximumNumberOfUsersSetting $maximumNumberOfUsersCustomSetting;

    /**
     * @var DaysThatVybesCanBeOrderedSetting
     */
    protected DaysThatVybesCanBeOrderedSetting $daysThatVybesCanBeOrderedDefaultSetting;

    /**
     * @var DaysThatVybesCanBeOrderedSetting
     */
    protected DaysThatVybesCanBeOrderedSetting $daysThatVybesCanBeOrderedCustomSetting;

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
     *
     * @param Vybe|null $vybe
     * @param User|null $user
     */
    public function __construct(
        ?Vybe $vybe,
        ?User $user = null
    )
    {
        /** @var Vybe vybe */
        $this->vybe = $vybe;

        /** @var UserHandlingFeesSetting userDefaultHandlingFeesSetting */
        $this->userDefaultHandlingFeesSetting = new UserHandlingFeesSetting();

        /** @var UserHandlingFeesSetting userCustomHandlingFeesSetting */
        $this->userCustomHandlingFeesSetting = new UserHandlingFeesSetting();

        /** @var VybeHandlingFeesSetting vybeDefaultHandlingFeesSetting */
        $this->vybeDefaultHandlingFeesSetting = new VybeHandlingFeesSetting();

        /** @var VybeHandlingFeesSetting vybeCustomHandlingFeesSetting */
        $this->vybeCustomHandlingFeesSetting = new VybeHandlingFeesSetting();

        /** @var MaximumNumberOfUsersSetting maximumNumberOfUsersDefaultSetting */
        $this->maximumNumberOfUsersDefaultSetting = new MaximumNumberOfUsersSetting();

        /** @var MaximumNumberOfUsersSetting maximumNumberOfUsersCustomSetting */
        $this->maximumNumberOfUsersCustomSetting = new MaximumNumberOfUsersSetting();

        /** @var DaysThatVybesCanBeOrderedSetting daysThatVybesCanBeOrderedDefaultSetting */
        $this->daysThatVybesCanBeOrderedDefaultSetting = new DaysThatVybesCanBeOrderedSetting();

        /** @var DaysThatVybesCanBeOrderedSetting daysThatVybesCanBeOrderedCustomSetting */
        $this->daysThatVybesCanBeOrderedCustomSetting = new DaysThatVybesCanBeOrderedSetting();

        /** @var VybesPricesSetting vybesPricesDefaultSetting */
        $this->vybesPricesDefaultSetting = new VybesPricesSetting();

        /** @var DisableVybeCreationSetting disableVybeCreationDefaultSetting */
        $this->disableVybeCreationDefaultSetting = new DisableVybeCreationSetting();

        /** @var DisableVybeInteractionTypesSetting disableVybeInteractionTypesDefaultSetting */
        $this->disableVybeInteractionTypesDefaultSetting = new DisableVybeInteractionTypesSetting();

        if ($vybe || $user) {
            if ($vybe) {

                /**
                 * Setting vybe to vybe custom settings
                 */
                $this->vybeCustomHandlingFeesSetting->setVybe(
                    $vybe
                );
            }

            /**
             * Setting user to user custom settings
             */
            $this->userCustomHandlingFeesSetting->setUser(
                $vybe ? $vybe->user : $user
            );

            $this->maximumNumberOfUsersCustomSetting->setUser(
                $vybe ? $vybe->user : $user
            );

            $this->daysThatVybesCanBeOrderedCustomSetting->setUser(
                $vybe ? $vybe->user : $user
            );
        }
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
            ],
            'custom' => [
                'handling_fees' => [
                    'seller_handling_fee'  => $this->userCustomHandlingFeesSetting->getSellerHandlingFee(false),
                    'vybe_handling_fee'    => $this->vybeCustomHandlingFeesSetting->getVybeSellerHandlingFee(false),
                    'tipping_handling_fee' => $this->vybeCustomHandlingFeesSetting->getVybeTippingHandlingFee(false)
                ],
                'maximum_number_of_users' => [
                    'group_vybes' => $this->maximumNumberOfUsersCustomSetting->getGroupVybes(false),
                    'events'      => $this->maximumNumberOfUsersCustomSetting->getEvents(false)
                ],
                'days_that_vybes_can_be_ordered' => [
                    'solo_vybes.minimum_days'  => $this->daysThatVybesCanBeOrderedCustomSetting->getSoloVybesMinimumDays(false),
                    'solo_vybes.maximum_days'  => $this->daysThatVybesCanBeOrderedCustomSetting->getSoloVybesMaximumDays(false),
                    'group_vybes.minimum_days' => $this->daysThatVybesCanBeOrderedCustomSetting->getGroupVybesMinimumDays(false),
                    'group_vybes.maximum_days' => $this->daysThatVybesCanBeOrderedCustomSetting->getGroupVybesMaximumDays(false)
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
