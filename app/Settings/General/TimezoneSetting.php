<?php

namespace App\Settings\General;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Timezone\Timezone;
use App\Settings\Base\GeneralSetting;
use App\Settings\General\Interfaces\TimezoneSettingInterface;

/**
 * Class TimezoneSetting
 *
 * @package App\Settings\General
 */
class TimezoneSetting extends GeneralSetting implements TimezoneSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Time zone';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'time_zone';

    /**
     * Setting description
     *
     * @var string
     */
    protected string $description = 'The time zone selected here will apply to the entire administration area of the platform, but will not affect any of the user-selected time zones.';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $timezone = [
        'code'           => 'timezone',
        'name'           => 'Timezone',
        'type'           => 'integer',
        'icon'           => null,
        'original_value' => 1
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return Timezone|null
     *
     * @throws DatabaseException
     */
    public function getTimezone() : ?Timezone
    {
        /**
         * Getting default general setting
         */
        $generalSetting = $this->generalSettingRepository->findByCode(
            $this->code,
            $this->timezone['code']
        );

        /**
         * Checking default general setting existence
         */
        if ($generalSetting) {
            return $this->timezoneRepository->findById(
                (int) $generalSetting->value
            );
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param int $value
     *
     * @throws DatabaseException
     */
    public function setTimezone(
        int $value
    ) : void
    {
        /**
         * Updating general setting
         */
        $this->generalSettingRepository->updateValueByCode(
            $this->code,
            $this->timezone['code'],
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
                    $this->timezone,
                    $this->getTimezone()
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
         * Checking default general setting existence
         */
        if (!$this->generalSettingRepository->existsByCode(
            $this->code,
            $this->timezone['code']
        )) {

            /**
             * Creating default general setting
             */
            $this->generalSettingRepository->store(
                $this->code,
                $this->timezone['code'],
                $this->timezone['original_value']
            );
        }
    }
}
