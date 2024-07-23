<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\PendingPayoutDelayTipSettingInterface;

/**
 * Class PendingPayoutDelayTipSetting
 *
 * @package App\Settings\User
 */
class PendingPayoutDelayTipSetting extends UserSetting implements PendingPayoutDelayTipSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Pending payout (Pending balance) delay (tip)';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'pending_payout_delay_tip';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $hours = [
        'code'           => 'hours',
        'name'           => 'Hours',
        'type'           => 'integer',
        'icon'           => 'calendar',
        'custom'         => true,
        'original_value' => 5
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getHours() : ?int
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
                $this->hours['code']
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
            $this->hours['code']
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
    public function setHours(
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
                $this->hours['code'],
                $value,
                $active
            );
        } else {

            /**
             * Updating user setting
             */
            $this->userSettingRepository->updateValueByCodeDefault(
                $this->code,
                $this->hours['code'],
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
                $this->processField(
                    $customOnly,
                    $this->hours,
                    $this->getHours()
                )
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
            $this->hours['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->hours['code'],
                $this->hours['original_value'],
                true
            );
        }
    }
}