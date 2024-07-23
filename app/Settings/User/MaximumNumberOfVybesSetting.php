<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\MaximumNumberOfVybesSettingInterface;

/**
 * Class MaximumNumberOfVybesSetting
 *
 * @package App\Settings\User
 */
class MaximumNumberOfVybesSetting extends UserSetting implements MaximumNumberOfVybesSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Maximum number of published vybes per user';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'maximum_number_of_vybes';

    /**
     * Setting description
     *
     * @var string
     */
    protected string $description = 'A number cannot be reduced if there are users without active custom vybe settings who have published more vybes than that number.';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $soloVybes = [
        'code'           => 'solo_vybes',
        'name'           => 'Solo vybes',
        'type'           => 'integer',
        'icon'           => 'joystick',
        'custom'         => true,
        'original_value' => 10
    ];

    /**
     * @var array
     */
    protected array $groupVybes = [
        'code'           => 'group_vybes',
        'name'           => 'Group vybes',
        'type'           => 'integer',
        'icon'           => 'joystick',
        'custom'         => true,
        'original_value' => 4
    ];

    /**
     * @var array
     */
    protected array $events = [
        'code'           => 'events',
        'name'           => 'Events',
        'type'           => 'integer',
        'icon'           => 'joystick',
        'custom'         => true,
        'original_value' => 4
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getSoloVybes() : ?int
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
                $this->soloVybes['code']
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
            $this->soloVybes['code']
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
    public function getGroupVybes() : ?int
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
                $this->groupVybes['code']
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
            $this->groupVybes['code']
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
    public function getEvents() : ?int
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
                $this->events['code']
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
            $this->events['code']
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
    public function setSoloVybes(
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
                $this->soloVybes['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->soloVybes['code'],
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
    public function setGroupVybes(
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
                $this->groupVybes['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->groupVybes['code'],
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
    public function setEvents(
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
                $this->events['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->events['code'],
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
            'code'        => $this->code,
            'name'        => $this->name,
            'description' => $this->description,
            'children'    => array_filter([
                $this->processField(
                    $customOnly,
                    $this->soloVybes,
                    $this->getSoloVybes()
                ),
                $this->processField(
                    $customOnly,
                    $this->groupVybes,
                    $this->getGroupVybes()
                ),
                $this->processField(
                    $customOnly,
                    $this->events,
                    $this->getEvents()
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
            $this->soloVybes['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->soloVybes['code'],
                $this->soloVybes['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->groupVybes['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->groupVybes['code'],
                $this->groupVybes['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->events['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->events['code'],
                $this->events['original_value'],
                true
            );
        }
    }
}