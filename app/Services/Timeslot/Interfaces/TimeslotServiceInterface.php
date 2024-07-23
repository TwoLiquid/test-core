<?php

namespace App\Services\Timeslot\Interfaces;

use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Schedule;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TimeslotServiceInterface
 *
 * @package App\Services\Timeslot\Interfaces
 */
interface TimeslotServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param User $user
     * @param AppearanceCase $appearanceCase
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     * @param bool $exceptions
     *
     * @return bool
     */
    public function isAvailable(
        User $user,
        AppearanceCase $appearanceCase,
        Carbon $datetimeFrom,
        Carbon $datetimeTo,
        bool $exceptions
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     * @param int|null $offset
     *
     * @return Collection
     */
    public function getForCalendar(
        Vybe $vybe,
        Carbon $datetimeFrom,
        Carbon $datetimeTo,
        ?int $offset
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $cartItems
     *
     * @return Collection
     */
    public function getByCartItems(
        Collection $cartItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Schedule $schedule
     * @param Collection $timeslots
     *
     * @return Collection
     */
    public function getForSchedule(
        Schedule $schedule,
        Collection $timeslots
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     *
     * @return Collection
     */
    public function getFutureForVybe(
        Vybe $vybe
    ) : Collection;
}
