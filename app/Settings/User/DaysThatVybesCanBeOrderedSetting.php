<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\DaysThatVybesCanBeOrderedSettingInterface;

/**
 * Class DaysThatVybesCanBeOrderedSetting
 *
 * @package App\Settings\User
 */
class DaysThatVybesCanBeOrderedSetting extends UserSetting implements DaysThatVybesCanBeOrderedSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Days that vybes can be ordered in advance';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'days_that_vybes_can_be_ordered';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $soloVybesMinimumDays = [
        'code'           => 'solo_vybes.minimum_days',
        'name'           => 'Minimum days',
        'type'           => 'integer',
        'icon'           => 'calendar',
        'custom'         => true,
        'original_value' => 6
    ];

    /**
     * @var array
     */
    protected array $soloVybesMaximumDays = [
        'code'           => 'solo_vybes.maximum_days',
        'name'           => 'Maximum days',
        'type'           => 'integer',
        'icon'           => 'calendar',
        'custom'         => true,
        'original_value' => 66
    ];

    /**
     * @var array
     */
    protected array $groupVybesMinimumDays = [
        'code'           => 'group_vybes.minimum_days',
        'name'           => 'Minimum days',
        'type'           => 'integer',
        'icon'           => 'calendar',
        'custom'         => true,
        'original_value' => 6
    ];

    /**
     * @var array
     */
    protected array $groupVybesMaximumDays = [
        'code'           => 'group_vybes.maximum_days',
        'name'           => 'Maximum days',
        'type'           => 'integer',
        'icon'           => 'calendar',
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
    public function getSoloVybesMinimumDays(
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
                $this->soloVybesMinimumDays['code']
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
                $this->soloVybesMinimumDays['code']
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
    public function getSoloVybesMaximumDays(
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
                $this->soloVybesMaximumDays['code']
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
                $this->soloVybesMaximumDays['code']
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
    public function getGroupVybesMinimumDays(
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
                $this->groupVybesMinimumDays['code']
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
                $this->groupVybesMinimumDays['code']
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
    public function getGroupVybesMaximumDays(
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
                $this->groupVybesMaximumDays['code']
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
                $this->groupVybesMaximumDays['code']
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
    public function setSoloVybesMinimumDays(
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
                $this->soloVybesMinimumDays['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->soloVybesMinimumDays['code'],
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
    public function setSoloVybesMaximumDays(
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
                $this->soloVybesMaximumDays['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->soloVybesMaximumDays['code'],
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
    public function setGroupVybesMinimumDays(
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
                $this->groupVybesMinimumDays['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->groupVybesMinimumDays['code'],
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
    public function setGroupVybesMaximumDays(
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
                $this->groupVybesMaximumDays['code'],
                (int) $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->groupVybesMaximumDays['code'],
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
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => [
                [
                    'code'     => 'solo_vybes',
                    'name'     => 'Solo vybes',
                    'children' => array_filter([
                        $this->processField(
                            $customOnly,
                            $this->soloVybesMinimumDays,
                            $this->getSoloVybesMinimumDays()
                        ),
                        $this->processField(
                            $customOnly,
                            $this->soloVybesMaximumDays,
                            $this->getSoloVybesMaximumDays()
                        )
                    ])
                ],
                [
                    'code'     => 'group_vybes',
                    'name'     => 'Group vybes',
                    'children' => array_filter([
                        $this->processField(
                            $customOnly,
                            $this->groupVybesMinimumDays,
                            $this->getGroupVybesMinimumDays()
                        ),
                        $this->processField(
                            $customOnly,
                            $this->groupVybesMaximumDays,
                            $this->getGroupVybesMaximumDays()
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
            $this->soloVybesMinimumDays['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->soloVybesMinimumDays['code'],
                $this->soloVybesMinimumDays['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->soloVybesMaximumDays['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->soloVybesMaximumDays['code'],
                $this->soloVybesMaximumDays['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->groupVybesMinimumDays['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->groupVybesMinimumDays['code'],
                $this->groupVybesMinimumDays['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->groupVybesMaximumDays['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->groupVybesMaximumDays['code'],
                $this->groupVybesMaximumDays['original_value'],
                true
            );
        }
    }
}