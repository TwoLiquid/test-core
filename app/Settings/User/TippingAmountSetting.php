<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\TippingAmountSettingInterface;

/**
 * Class TippingAmountSetting
 *
 * @package App\Settings\User
 */
class TippingAmountSetting extends UserSetting implements TippingAmountSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Tipping amount';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'tipping_amount';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $voiceChatMinimum = [
        'code'           => 'voice_chat.minimum',
        'name'           => 'Minimum',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 2
    ];

    /**
     * @var array
     */
    protected array $voiceChatMaximum = [
        'code'           => 'voice_chat.maximum',
        'name'           => 'Maximum',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 10
    ];

    /**
     * @var array
     */
    protected array $videoChatMinimum = [
        'code'           => 'video_chat.minimum',
        'name'           => 'Minimum',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 2
    ];

    /**
     * @var array
     */
    protected array $videoChatMaximum = [
        'code'           => 'video_chat.maximum',
        'name'           => 'Maximum',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 10
    ];

    /**
     * @var array
     */
    protected array $realLifeMinimum = [
        'code'           => 'real_life.minimum',
        'name'           => 'Minimum',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 2
    ];

    /**
     * @var array
     */
    protected array $realLifeMaximum = [
        'code'           => 'real_life.maximum',
        'name'           => 'Maximum',
        'type'           => 'integer',
        'icon'           => 'dollar',
        'custom'         => true,
        'original_value' => 10
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getVoiceChatMinimum() : ?int
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
                $this->voiceChatMinimum['code']
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
            $this->voiceChatMinimum['code']
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
    public function getVoiceChatMaximum() : ?int
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
                $this->voiceChatMaximum['code']
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
            $this->voiceChatMaximum['code']
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
    public function getVideoChatMinimum() : ?int
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
                $this->videoChatMinimum['code']
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
            $this->videoChatMinimum['code']
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
    public function getVideoChatMaximum() : ?int
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
                $this->videoChatMaximum['code']
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
            $this->videoChatMaximum['code']
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
    public function getRealLifeMinimum() : ?int
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
                $this->realLifeMinimum['code']
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
            $this->realLifeMinimum['code']
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
    public function getRealLifeMaximum() : ?int
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
                $this->realLifeMaximum['code']
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
            $this->realLifeMaximum['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
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
    public function setVoiceChatMinimum(
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
                $this->voiceChatMinimum['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->voiceChatMinimum['code'],
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
    public function setVoiceChatMaximum(
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
                $this->voiceChatMaximum['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->voiceChatMaximum['code'],
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
    public function setVideoChatMinimum(
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
                $this->videoChatMinimum['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->videoChatMinimum['code'],
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
    public function setVideoChatMaximum(
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
                $this->videoChatMaximum['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->videoChatMaximum['code'],
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
    public function setRealLifeMinimum(
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
                $this->realLifeMinimum['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->realLifeMinimum['code'],
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
    public function setRealLifeMaximum(
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
                $this->realLifeMaximum['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->realLifeMaximum['code'],
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
            'children' => [
                [
                    'code'     => 'voice_chat',
                    'name'     => 'Tip for voice chat vybes',
                    'children' => array_filter([
                        $this->processField(
                            $customOnly,
                            $this->voiceChatMinimum,
                            $this->getVoiceChatMinimum()
                        ),
                        $this->processField(
                            $customOnly,
                            $this->voiceChatMaximum,
                            $this->getVoiceChatMaximum()
                        )
                    ])
                ],
                [
                    'code'     => 'video_chat',
                    'name'     => 'Tip for video chat vybes',
                    'children' => array_filter([
                        $this->processField(
                            $customOnly,
                            $this->videoChatMinimum,
                            $this->getVideoChatMinimum()
                        ),
                        $this->processField(
                            $customOnly,
                            $this->videoChatMaximum,
                            $this->getVideoChatMaximum()
                        )
                    ])
                ],
                [
                    'code'     => 'real_life',
                    'name'     => 'Tip for real life vybes',
                    'children' => array_filter([
                        $this->processField(
                            $customOnly,
                            $this->realLifeMinimum,
                            $this->getRealLifeMinimum()
                        ),
                        $this->processField(
                            $customOnly,
                            $this->realLifeMaximum,
                            $this->getRealLifeMaximum()
                        )
                    ])
                ]
            ]
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
            $this->voiceChatMinimum['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->voiceChatMinimum['code'],
                $this->voiceChatMinimum['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->voiceChatMaximum['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->voiceChatMaximum['code'],
                $this->voiceChatMaximum['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->videoChatMinimum['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->videoChatMinimum['code'],
                $this->videoChatMinimum['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->videoChatMaximum['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->videoChatMaximum['code'],
                $this->videoChatMaximum['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->realLifeMinimum['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->realLifeMinimum['code'],
                $this->realLifeMinimum['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->realLifeMaximum['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->realLifeMaximum['code'],
                $this->realLifeMaximum['original_value'],
                true
            );
        }
    }
}