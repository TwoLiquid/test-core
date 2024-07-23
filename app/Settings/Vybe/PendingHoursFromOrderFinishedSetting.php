<?php

namespace App\Settings\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\VybeSetting;
use App\Settings\Vybe\Interfaces\PendingHoursFromOrderSettingInterface;

/**
 * Class PendingHoursFromOrderFinishedSetting
 *
 * @package App\Settings\Vybe
 */
class PendingHoursFromOrderFinishedSetting extends VybeSetting implements PendingHoursFromOrderSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Pending hours from order ID finished to sale receipt paid';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'pending_hours_from_order_finished';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $pendingHours = [
        'code'           => 'pending_hours',
        'name'           => 'Pending hours',
        'type'           => 'integer',
        'icon'           => 'calendar',
        'custom'         => true,
        'original_value' => 72
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getPendingHours() : ?int
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->vybeSettingRepository->findByCodeDefault(
            $this->code,
            $this->pendingHours['code']
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
     *
     * @throws DatabaseException
     */
    public function setPendingHours(
        int $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->vybeSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->pendingHours['code'],
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
                    $this->pendingHours,
                    $this->getPendingHours()
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
            $this->pendingHours['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->vybeSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->pendingHours['code'],
                $this->pendingHours['original_value'],
                true
            );
        }
    }
}