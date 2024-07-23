<?php

namespace App\Settings\Request;

use App\Exceptions\DatabaseException;
use App\Settings\Base\RequestSetting;
use App\Settings\Request\Interfaces\UserRequestsSettingInterface;

/**
 * Class UserRequestsSetting
 *
 * @package App\Settings\Request
 */
class UserRequestsSetting extends RequestSetting implements UserRequestsSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'User requests';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'user_requests';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $permissionForProfileRequests = [
        'code'           => 'permission_for_profile_requests',
        'name'           => 'Require admin permission for profile requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForIdVerificationRequests = [
        'code'           => 'permission_for_id_verification_requests',
        'name'           => 'Require admin permission for ID verification requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForDeactivationRequests = [
        'code'           => 'permission_for_deactivation_requests',
        'name'           => 'Require admin permission for deactivation requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForUnsuspensionRequests = [
        'code'           => 'permission_for_unsuspension_requests',
        'name'           => 'Require admin permission for unsuspension requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForDeletionRequests = [
        'code'           => 'permission_for_deletion_requests',
        'name'           => 'Require admin permission for deletion requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getPermissionForProfileRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForProfileRequests['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getPermissionForIdVerificationRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForIdVerificationRequests['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getPermissionForDeactivationRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForDeactivationRequests['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getPermissionForUnsuspensionRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForUnsuspensionRequests['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getPermissionForDeletionRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForDeletionRequests['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
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
    public function setPermissionForProfileRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForProfileRequests['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForIdVerificationRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForIdVerificationRequests['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForDeactivationRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForDeactivationRequests['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForUnsuspensionRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForUnsuspensionRequests['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForDeletionRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForDeletionRequests['code'],
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
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => array_filter([
                $this->processField(
                    false,
                    $this->permissionForProfileRequests,
                    $this->getPermissionForProfileRequests()
                ),
                $this->processField(
                    false,
                    $this->permissionForIdVerificationRequests,
                    $this->getPermissionForIdVerificationRequests()
                ),
                $this->processField(
                    false,
                    $this->permissionForDeactivationRequests,
                    $this->getPermissionForDeactivationRequests()
                ),
                $this->processField(
                    false,
                    $this->permissionForUnsuspensionRequests,
                    $this->getPermissionForUnsuspensionRequests()
                ),
                $this->processField(
                    false,
                    $this->permissionForDeletionRequests,
                    $this->getPermissionForDeletionRequests()
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
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForProfileRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForProfileRequests['code'],
                $this->permissionForProfileRequests['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForIdVerificationRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForIdVerificationRequests['code'],
                $this->permissionForIdVerificationRequests['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForDeactivationRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForDeactivationRequests['code'],
                $this->permissionForDeactivationRequests['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForUnsuspensionRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForUnsuspensionRequests['code'],
                $this->permissionForUnsuspensionRequests['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForDeletionRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForDeletionRequests['code'],
                $this->permissionForDeletionRequests['original_value']
            );
        }
    }
}