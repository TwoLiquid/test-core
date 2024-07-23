<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\RegistrationSettingInterface;

/**
 * Class RegistrationSetting
 *
 * @package App\Settings\User
 */
class RegistrationSetting extends UserSetting implements RegistrationSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Registration';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'registration';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $disableRegistration = [
        'code'           => 'disable_registration',
        'name'           => 'Disable registration',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => false
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getDisableRegistration() : ?bool
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->disableRegistration['code']
        );

        /**
         * Checking default user setting existence
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
    public function setDisableRegistration(
        bool $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->disableRegistration['code'],
            $value
        );
    }

    //--------------------------------------------------------------------------
    // Build

    /**
     * @param bool|null $customOnly
     *
     * @return array|null
     *
     * @throws DatabaseException
     */
    public function build(
        ?bool $customOnly = false
    ) : ?array
    {
        $data = [
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => array_filter([
                $this->processField(
                    $customOnly,
                    $this->disableRegistration,
                    $this->getDisableRegistration()
                )
            ])
        ];

        if (empty($data['children'])) {
            return null;
        }

        return $data;
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
            $this->disableRegistration['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->disableRegistration['code'],
                $this->disableRegistration['original_value'],
                true
            );
        }
    }
}