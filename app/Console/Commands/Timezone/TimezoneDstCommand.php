<?php

namespace App\Console\Commands\Timezone;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Timezone\Timezone;
use App\Repositories\Timezone\TimezoneRepository;
use App\Repositories\Timezone\TimezoneTimeChangeRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class TimezoneDstCommand
 *
 * @package App\Console\Commands\Timezone
 */
class TimezoneDstCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timezones:dst';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Timezones DST update command.';

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * @var TimezoneTimeChangeRepository
     */
    protected TimezoneTimeChangeRepository $timezoneTimeChangeRepository;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();

        /** @var TimezoneTimeChangeRepository timezoneTimeChangeRepository */
        $this->timezoneTimeChangeRepository = new TimezoneTimeChangeRepository();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function handle() : int
    {
        $this->info('Starting timezones DST updating...' . PHP_EOL);

        /**
         * Getting timezones
         */
        $timezones = $this->timezoneRepository->getAllHaveDst();

        /**
         * Getting current date time
         */
        $currentDateTime = Carbon::now();

        /** @var Timezone $timezone */
        foreach ($timezones as $timezone) {

            /**
             * Getting timezone time change
             */
            $currentTimeChange = $this->timezoneTimeChangeRepository->findByTimezone(
                $timezone,
                $currentDateTime
            );

            /**
             * Checking timezone time change existence
             */
            if (!$currentTimeChange) {
                $this->error('No time changes for ' . $timezone->external_id . ' found.' . PHP_EOL);
            } else {

                /**
                 * Checking DST difference
                 */
                if ($timezone->in_dst != $currentTimeChange->to_dst) {

                    /**
                     * Updating timezone
                     */
                    $this->timezoneRepository->updateInDst(
                        $timezone,
                        $currentTimeChange->to_dst
                    );

                    /**
                     * Checking timezone time change to dst flag
                     */
                    if ($currentTimeChange->to_dst) {
                        $this->info($timezone->external_id . ' has been moved to DST.' . PHP_EOL);
                    } else {
                        $this->info($timezone->external_id . ' has been moved from DST.' . PHP_EOL);
                    }
                }
            }
        }

        $this->info('Timezones DST has been updated successfully!');

        return 1;
    }
}
