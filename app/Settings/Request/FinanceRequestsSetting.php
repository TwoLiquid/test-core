<?php

namespace App\Settings\Request;

use App\Exceptions\DatabaseException;
use App\Settings\Base\RequestSetting;
use App\Settings\Request\Interfaces\FinanceRequestsSettingInterface;

/**
 * Class FinanceRequestsSetting
 *
 * @package App\Settings\Request
 */
class FinanceRequestsSetting extends RequestSetting implements FinanceRequestsSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Finance requests';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'finance_requests';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $permissionForNewBillingChangeRequests = [
        'code'           => 'permission_for_new_billing_change_requests',
        'name'           => 'Require admin permission for new billing change requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForWithdrawalRequests = [
        'code'           => 'permission_for_withdrawal_requests',
        'name'           => 'Require admin permission for withdrawal requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $permissionForPayoutMethodRequests = [
        'code'           => 'permission_for_payout_method_requests',
        'name'           => 'Require admin permission for payout method requests',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => false
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getPermissionForNewBillingChangeRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForNewBillingChangeRequests['code']
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
    public function getPermissionForWithdrawalRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForWithdrawalRequests['code']
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
    public function getPermissionForPayoutMethodRequests() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->permissionForPayoutMethodRequests['code']
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
    public function setPermissionForNewBillingChangeRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForNewBillingChangeRequests['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForWithdrawalRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForWithdrawalRequests['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setPermissionForPayoutMethodRequests(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->permissionForPayoutMethodRequests['code'],
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
                    $this->permissionForNewBillingChangeRequests,
                    $this->getPermissionForNewBillingChangeRequests()
                ),
                $this->processField(
                    false,
                    $this->permissionForWithdrawalRequests,
                    $this->getPermissionForWithdrawalRequests()
                ),
                $this->processField(
                    false,
                    $this->permissionForPayoutMethodRequests,
                    $this->getPermissionForPayoutMethodRequests()
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
            $this->permissionForNewBillingChangeRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForNewBillingChangeRequests['code'],
                $this->permissionForNewBillingChangeRequests['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForWithdrawalRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForWithdrawalRequests['code'],
                $this->permissionForWithdrawalRequests['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->permissionForPayoutMethodRequests['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->permissionForPayoutMethodRequests['code'],
                $this->permissionForPayoutMethodRequests['original_value']
            );
        }
    }
}