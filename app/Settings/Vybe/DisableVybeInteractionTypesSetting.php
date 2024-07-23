<?php

namespace App\Settings\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\VybeSetting;
use App\Settings\Vybe\Interfaces\DisableVybeInteractionTypesSettingInterface;

/**
 * Class DisableVybeInteractionTypesSetting
 *
 * @package App\Settings\Vybe
 */
class DisableVybeInteractionTypesSetting extends VybeSetting implements DisableVybeInteractionTypesSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Disable interaction types';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'disable_interaction_types';

    /**
     * Setting description
     *
     * @var string
     */
    protected string $description = 'An interaction type cannot be disabled if is being used by one or more vybes, regardless of their status.';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $voiceChat = [
        'code'           => 'voice_chat',
        'name'           => 'Voice chat',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $videoChat = [
        'code'           => 'video_chat',
        'name'           => 'Video chat',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $realLife = [
        'code'           => 'real_life',
        'name'           => 'Real life',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => true
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getVoiceChat() : ?bool
    {
        /**
         * Getting default vybe setting
         */
        $userSetting = $this->vybeSettingRepository->findByCodeDefault(
            $this->code,
            $this->voiceChat['code']
        );

        /**
         * Checking default vybe setting existence
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
    public function getVideoChat() : ?bool
    {
        /**
         * Getting default vybe setting
         */
        $userSetting = $this->vybeSettingRepository->findByCodeDefault(
            $this->code,
            $this->videoChat['code']
        );

        /**
         * Checking default vybe setting existence
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
    public function getRealLife() : ?bool
    {
        /**
         * Getting default vybe setting
         */
        $userSetting = $this->vybeSettingRepository->findByCodeDefault(
            $this->code,
            $this->realLife['code']
        );

        /**
         * Checking default vybe setting existence
         */
        if ($userSetting) {
            return (bool) $userSetting->value;
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setVoiceChat(
        bool $value
    ) : void
    {
        /**
         * Updating vybe setting
         */
        $this->vybeSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->voiceChat['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setVideoChat(
        bool $value
    ) : void
    {
        /**
         * Updating vybe setting
         */
        $this->vybeSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->videoChat['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setRealLife(
        bool $value
    ) : void
    {
        /**
         * Updating vybe setting
         */
        $this->vybeSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->realLife['code'],
            $value
        );
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
            'code'        => $this->code,
            'name'        => $this->name,
            'description' => $this->description,
            'children'    => array_filter([
                $this->processField(
                    false,
                    $this->voiceChat,
                    $this->getVoiceChat()
                ),
                $this->processField(
                    false,
                    $this->videoChat,
                    $this->getVideoChat()
                ),
                $this->processField(
                    false,
                    $this->realLife,
                    $this->getRealLife()
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
         * Checking default vybe setting existence
         */
        if (!$this->vybeSettingRepository->existsByCodeDefault(
            $this->code,
            $this->voiceChat['code']
        )) {

            /**
             * Creating default vybe setting
             */
            $this->vybeSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->voiceChat['code'],
                $this->voiceChat['original_value'],
                true
            );
        }

        /**
         * Checking default vybe setting existence
         */
        if (!$this->vybeSettingRepository->existsByCodeDefault(
            $this->code,
            $this->videoChat['code']
        )) {

            /**
             * Creating default vybe setting
             */
            $this->vybeSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->videoChat['code'],
                $this->videoChat['original_value'],
                true
            );
        }

        /**
         * Checking default vybe setting existence
         */
        if (!$this->vybeSettingRepository->existsByCodeDefault(
            $this->code,
            $this->realLife['code']
        )) {

            /**
             * Creating default vybe setting
             */
            $this->vybeSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->realLife['code'],
                $this->realLife['original_value'],
                true
            );
        }
    }
}