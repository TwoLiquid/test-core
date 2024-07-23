<?php

namespace App\Settings\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\VybeSetting;
use App\Settings\Vybe\Interfaces\DisableVybeCreationSettingInterface;

/**
 * Class DisableVybeCreationSetting
 *
 * @package App\Settings\Vybe
 */
class DisableVybeCreationSetting extends VybeSetting implements DisableVybeCreationSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Disable vybe creation';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'disable_vybe_creation';

    /**
     * Setting description
     *
     * @var string
     */
    protected string $description = 'While active, the toggles below will prevent new vybes from being created. The restriction can be applied to unverified users or all users. Existing vybes will be unaffected.';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $preventUnverifiedUsers = [
        'code'           => 'prevent_unverified_users',
        'name'           => 'Prevent unverified users from creating new vybes',
        'type'           => 'boolean',
        'icon'           => null,
        'custom'         => false,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $preventAllUsers = [
        'code'           => 'prevent_all_users',
        'name'           => 'Prevent all users from creating new vybes',
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
    public function getPreventUnverifiedUsers() : ?bool
    {
        /**
         * Getting default vybe setting
         */
        $userSetting = $this->vybeSettingRepository->findByCodeDefault(
            $this->code,
            $this->preventUnverifiedUsers['code']
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
    public function getPreventAllUsers() : ?bool
    {
        /**
         * Getting default vybe setting
         */
        $userSetting = $this->vybeSettingRepository->findByCodeDefault(
            $this->code,
            $this->preventAllUsers['code']
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
    public function setPreventUnverifiedUsers(
        bool $value
    ) : void
    {
        /**
         * Updating vybe setting
         */
        $this->vybeSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->preventUnverifiedUsers['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPreventAllUsers(
        bool $value
    ) : void
    {
        /**
         * Updating vybe setting
         */
        $this->vybeSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->preventAllUsers['code'],
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
                    $this->preventUnverifiedUsers,
                    $this->getPreventUnverifiedUsers()
                ),
                $this->processField(
                    false,
                    $this->preventAllUsers,
                    $this->getPreventAllUsers()
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
            $this->preventUnverifiedUsers['code']
        )) {

            /**
             * Creating default vybe setting
             */
            $this->vybeSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->preventUnverifiedUsers['code'],
                $this->preventUnverifiedUsers['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->vybeSettingRepository->existsByCodeDefault(
            $this->code,
            $this->preventAllUsers['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->vybeSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->preventAllUsers['code'],
                $this->preventAllUsers['original_value'],
                true
            );
        }
    }
}