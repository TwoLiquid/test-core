<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\MaximumNumberOfUsersSettingInterface;

/**
 * Class MaximumNumberOfUsersSetting
 *
 * @package App\Settings\User
 */
class MaximumNumberOfUsersSetting extends UserSetting implements MaximumNumberOfUsersSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Maximum number of users per vybe type';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'maximum_number_of_users';

    /**
     * Setting description
     *
     * @var string
     */
    protected string $description = 'These numbers determine the maximum number of users in the Group Vybes and Events that this user publishes, respectively. Only applies to future vybes.';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $groupVybes = [
        'code'           => 'group_vybes',
        'name'           => 'Group vybes',
        'type'           => 'integer',
        'icon'           => 'joystick',
        'custom'         => true,
        'original_value' => 11
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
        'original_value' => 66
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
    public function getGroupVybes(
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
                $this->groupVybes['code']
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
                $this->groupVybes['code']
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
    public function getEvents(
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
                $this->events['code']
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
                $this->events['code']
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
    public function setGroupVybes(
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
                $this->groupVybes['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->groupVybes['code'],
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
    public function setEvents(
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
                $this->events['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->events['code'],
                (int) $value
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