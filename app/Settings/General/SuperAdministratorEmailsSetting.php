<?php

namespace App\Settings\General;

use App\Exceptions\DatabaseException;
use App\Settings\Base\GeneralSetting;
use App\Settings\General\Interfaces\SuperAdministratorEmailsSettingInterface;

/**
 * Class SuperAdministratorEmailsSetting
 *
 * @package App\Settings\General
 */
class SuperAdministratorEmailsSetting extends GeneralSetting implements SuperAdministratorEmailsSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Super administrator emails';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'super_administrator_emails';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $emails = [
        'code'           => 'emails',
        'name'           => 'Emails',
        'type'           => 'array',
        'icon'           => 'email',
        'original_value' => []
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return array|null
     *
     * @throws DatabaseException
     */
    public function getEmails() : ?array
    {
        /**
         * Getting default general setting
         */
        $generalSetting = $this->generalSettingRepository->findByCode(
            $this->code,
            $this->emails['code']
        );

        /**
         * Checking default general setting existence
         */
        if ($generalSetting) {
            return (array) $generalSetting->value;
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param array $value
     *
     * @throws DatabaseException
     */
    public function setEmails(
        array $value
    ) : void
    {
        /**
         * Updating general setting
         */
        $this->generalSettingRepository->updateValueByCode(
            $this->code,
            $this->emails['code'],
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
                    $this->emails,
                    $this->getEmails()
                )
            ])
        ];
    }

    //--------------------------------------------------------------------------
    // Seed

    /**
     * @return void
     *
     * @throws DatabaseException
     */
    public function seed() : void
    {
        /**
         * Checking default general setting existence
         */
        if (!$this->generalSettingRepository->existsByCode(
            $this->code,
            $this->emails['code']
        )) {

            /**
             * Creating default general setting
             */
            $this->generalSettingRepository->store(
                $this->code,
                $this->emails['code'],
                []
            );
        }
    }
}