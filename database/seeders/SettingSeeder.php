<?php

namespace Database\Seeders;

use App\Exceptions\DatabaseException;
use App\Settings\General\SuperAdministratorEmailsSetting;
use App\Settings\General\TimezoneSetting;
use App\Settings\Request\FinanceRequestsSetting;
use App\Settings\Request\ReviewApprovalSetting;
use App\Settings\Request\UserRequestsSetting;
use App\Settings\Request\VybeRequestsSetting;
use App\Settings\User\AddFundsSetting;
use App\Settings\User\DaysThatVybesCanBeOrderedSetting;
use App\Settings\User\HandlingFeesSetting;
use App\Settings\User\MaximumNumberOfUsersSetting;
use App\Settings\User\MaximumNumberOfVybesSetting;
use App\Settings\User\PendingPayoutDelaySetting;
use App\Settings\User\PendingPayoutDelayTipSetting;
use App\Settings\User\RegistrationSetting;
use App\Settings\User\StreamerLabelSetting;
use App\Settings\User\TippingAmountSetting;
use App\Settings\User\VybesPricesSetting;
use App\Settings\User\WithdrawalSetting;
use App\Settings\Vybe\DisableVybeCreationSetting;
use App\Settings\Vybe\DisableVybeInteractionTypesSetting;
use App\Settings\Vybe\PendingHoursFromOrderFinishedSetting;
use Illuminate\Database\Seeder;

/**
 * Class SettingSeeder
 *
 * @package Database\Seeders
 */
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        /**
         * Seeding user settings
         */
        $registrationSetting = new RegistrationSetting();
        $registrationSetting->seed();

        $addFundsSetting = new AddFundsSetting();
        $addFundsSetting->seed();

        $handlingFeeSetting = new HandlingFeesSetting();
        $handlingFeeSetting->seed();

        $pendingPayoutDelaySetting = new PendingPayoutDelaySetting();
        $pendingPayoutDelaySetting->seed();

        $daysThatVybesCanBeOrderedSetting = new DaysThatVybesCanBeOrderedSetting();
        $daysThatVybesCanBeOrderedSetting->seed();

        $withdrawalSetting = new WithdrawalSetting();
        $withdrawalSetting->seed();

        $maximumNumberOfVybesSetting = new MaximumNumberOfVybesSetting();
        $maximumNumberOfVybesSetting->seed();

        $maximumNumberOfUsersSetting = new MaximumNumberOfUsersSetting();
        $maximumNumberOfUsersSetting->seed();

        $vybePricesSetting = new VybesPricesSetting();
        $vybePricesSetting->seed();

        $tippingAmountSetting = new TippingAmountSetting();
        $tippingAmountSetting->seed();

        $pendingPayoutDelayTipSetting = new PendingPayoutDelayTipSetting();
        $pendingPayoutDelayTipSetting->seed();

        $streamerLabelSetting = new StreamerLabelSetting();
        $streamerLabelSetting->seed();

        /**
         * Seeding vybe settings
         */
        $disableVybeCreationSetting = new DisableVybeCreationSetting();
        $disableVybeCreationSetting->seed();

        $disableVybeInteractionTypesSetting = new DisableVybeInteractionTypesSetting();
        $disableVybeInteractionTypesSetting->seed();

        $pendingHoursFromOrderSetting = new PendingHoursFromOrderFinishedSetting();
        $pendingHoursFromOrderSetting->seed();

        /**
         * Seeding request settings
         */
        $userRequestsSetting = new UserRequestsSetting();
        $userRequestsSetting->seed();

        $vybeRequestsSetting = new VybeRequestsSetting();
        $vybeRequestsSetting->seed();

        $financeRequestsSetting = new FinanceRequestsSetting();
        $financeRequestsSetting->seed();

        $reviewApprovalSetting = new ReviewApprovalSetting();
        $reviewApprovalSetting->seed();

        /**
         * Seeding general settings
         */
        $superAdministratorEmailsSetting = new SuperAdministratorEmailsSetting();
        $superAdministratorEmailsSetting->seed();

        $timezoneSetting = new TimezoneSetting();
        $timezoneSetting->seed();
    }
}
