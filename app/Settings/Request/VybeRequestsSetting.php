<?php

namespace App\Settings\Request;

use App\Exceptions\DatabaseException;
use App\Settings\Base\RequestSetting;
use App\Settings\Request\Interfaces\VybeRequestsSettingInterface;

/**
 * Class VybeRequestsSetting
 *
 * @package App\Settings\Request
 */
class VybeRequestsSetting extends RequestSetting implements VybeRequestsSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Vybe requests';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'vybe_requests';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $permissionForChangingVybes = [
        'code'           => 'permission_for_changing_vybes',
        'name'           => 'Require admin permission for changing vybes',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForPublishingVybes = [
        'code'           => 'permission_for_publishing_vybes',
        'name'           => 'Require admin permission for publishing vybes',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForUnpublishingVybes = [
        'code'           => 'permission_for_unpublishing_vybes',
        'name'           => 'Require admin permission for unpublishing vybes',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => false
    ];

    /**
     * @var array
     */
    protected array $permissionForUnsuspensionVybes = [
        'code'           => 'permission_for_unsuspension_vybes',
        'name'           => 'Require admin permission for unsuspension vybes',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForDeletionVybes = [
        'code'           => 'permission_for_deletion_vybes',
        'name'           => 'Require admin permission for deletion vybes',
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
    public function getPermissionForChangingVybes() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForChangingVybes['code']
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
    public function getPermissionForPublishingVybes() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForPublishingVybes['code']
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
    public function getPermissionForUnpublishingVybes() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForUnpublishingVybes['code']
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
    public function getPermissionForUnsuspensionVybes() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForUnsuspensionVybes['code']
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
    public function getPermissionForDeletionVybes() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForDeletionVybes['code']
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
    public function setPermissionForChangingVybes(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForChangingVybes['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForPublishingVybes(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForPublishingVybes['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForUnpublishingVybes(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForUnpublishingVybes['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForUnsuspensionVybes(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForUnsuspensionVybes['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForDeletionVybes(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForDeletionVybes['code'],
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
                    $this->permissionForChangingVybes,
                    $this->getPermissionForChangingVybes()
                ),
                $this->processField(
                    false,
                    $this->permissionForPublishingVybes,
                    $this->getPermissionForPublishingVybes()
                ),
                $this->processField(
                    false,
                    $this->permissionForUnpublishingVybes,
                    $this->getPermissionForUnpublishingVybes()
                ),
                $this->processField(
                    false,
                    $this->permissionForUnsuspensionVybes,
                    $this->getPermissionForUnsuspensionVybes()
                ),
                $this->processField(
                    false,
                    $this->permissionForDeletionVybes,
                    $this->getPermissionForDeletionVybes()
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
            $this->permissionForChangingVybes['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForChangingVybes['code'],
                $this->permissionForChangingVybes['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForPublishingVybes['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForPublishingVybes['code'],
                $this->permissionForPublishingVybes['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForUnpublishingVybes['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForUnpublishingVybes['code'],
                $this->permissionForUnpublishingVybes['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForUnsuspensionVybes['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForUnsuspensionVybes['code'],
                $this->permissionForUnsuspensionVybes['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForDeletionVybes['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForDeletionVybes['code'],
                $this->permissionForDeletionVybes['original_value']
            );
        }
    }
}