<?php

namespace App\Services\Timeslot;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\CartItem;
use App\Models\MySql\Schedule;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Services\Timeslot\Interfaces\TimeslotServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TimeslotService
 *
 * @package App\Services\Timeslot
 */
class TimeslotService implements TimeslotServiceInterface
{
    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * TimeslotService constructor
     */
    public function __construct()
    {
        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();
    }

    /**
     * @param User $user
     * @param AppearanceCase $appearanceCase
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     * @param bool $exceptions
     *
     * @return bool
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function isAvailable(
        User $user,
        AppearanceCase $appearanceCase,
        Carbon $datetimeFrom,
        Carbon $datetimeTo,
        bool $exceptions = true
    ) : bool
    {
        /**
         * Checking is timeslot in the past
         */
        if ($datetimeFrom->isPast()) {
            if ($exceptions === true) {
                throw new BaseException(
                    trans('exceptions/service/timeslot.' . __FUNCTION__ . '.timeslot.past'),
                    null,
                    422
                );
            }

            return false;
        }

        /**
         * Checking user is an owner
         */
        if ($appearanceCase->vybe
            ->user
            ->is($user)
        ) {
            if ($exceptions === true) {
                throw new BaseException(
                    trans('exceptions/service/timeslot.' . __FUNCTION__ . '.vybe.owner'),
                    null,
                    422
                );
            }

            return false;
        }

        /**
         * Checking timeslot range
         */
        if ($datetimeFrom->eq($datetimeTo)) {
            if ($exceptions === true) {
                throw new BaseException(
                    trans('exceptions/service/timeslot.' . __FUNCTION__ . '.timeslot.range'),
                    null,
                    422
                );
            }

            return false;
        }

        /**
         * Getting timeslot
         */
        $timeslot = $this->timeslotRepository->findForVybeBetweenDates(
            $appearanceCase->vybe,
            $datetimeFrom->format('Y-m-d H:i:s'),
            $datetimeTo->format('Y-m-d H:i:s')
        );

        /**
         * Checking timeslot existence
         */
        if ($timeslot) {

            /**
             * Checking vybe type
             */
            if ($appearanceCase->vybe->getType()->isSolo()) {
                if ($exceptions === true) {
                    throw new BaseException(
                        trans('exceptions/service/timeslot.' . __FUNCTION__ . '.timeslot.busy'),
                        null,
                        422
                    );
                }

                return false;
            } else {

                /**
                 * Checking vybe and timeslot users count
                 */
                if ($appearanceCase->vybe->user_count <= $timeslot->users_count) {
                    if ($exceptions === true) {
                        throw new BaseException(
                            trans('exceptions/service/timeslot.' . __FUNCTION__ . '.timeslot.full'),
                            null,
                            422
                        );
                    }

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param Vybe $vybe
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     * @param int|null $offset
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForCalendar(
        Vybe $vybe,
        Carbon $datetimeFrom,
        Carbon $datetimeTo,
        ?int $offset = 0
    ) : Collection
    {
        /**
         * Getting datetime range timeslots
         */
        $timeslots = $this->timeslotRepository->getForVybeBetweenDates(
            $vybe,
            $datetimeFrom,
            $datetimeTo
        );

        /**
         * Checking timezone offset
         */
        if ($offset != 0) {

            /**
             * Preparing a shifted timeslots collection
             */
            $shiftedTimeslots = new Collection();

            /** @var Timeslot $timeslot */
            foreach ($timeslots as $timeslot) {

                /**
                 * Applying timezone offset to timeslot
                 */
                $timeslot->datetime_from = Carbon::parse($timeslot->datetime_from)->addSeconds($offset);
                $timeslot->datetime_to = Carbon::parse($timeslot->datetime_to)->addSeconds($offset);

                /**
                 * Adding shifted timeslot to a collection
                 */
                $shiftedTimeslots->push(
                    $timeslot
                );
            }

            return $shiftedTimeslots;
        }

        return $timeslots;
    }

    /**
     * @param Collection $cartItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCartItems(
        Collection $cartItems
    ) : Collection
    {
        /**
         * Preparing a timeslot collection
         */
        $timeslots = new Collection();

        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {

            /**
             * Checking appearance case relation existence
             */
            if ($cartItem->relationLoaded('appearanceCase')) {

                /**
                 * Checking vybe relation existence
                 */
                if ($cartItem->appearanceCase->relationLoaded('vybe')) {

                    /**
                     * Getting timeslot
                     */
                    $timeslot = $this->timeslotRepository->findForVybeByDates(
                        $cartItem->appearanceCase->vybe,
                        $cartItem->datetime_from->format('Y-m-d H:i:s'),
                        $cartItem->datetime_to->format('Y-m-d H:i:s')
                    );

                    /**
                     * Checking timeslot existence
                     */
                    if ($timeslot) {

                        /**
                         * Adding timeslot to a collection
                         */
                        $timeslots->push(
                            $timeslot
                        );
                    }
                }
            }
        }

        return $timeslots;
    }

    /**
     * @param Schedule $schedule
     * @param Collection $timeslots
     *
     * @return Collection
     */
    public function getForSchedule(
        Schedule $schedule,
        Collection $timeslots
    ) : Collection
    {
        /**
         * Preparing a timeslot collection
         */
        $response = new Collection();

        /** @var Timeslot $timeslot */
        foreach ($timeslots as $timeslot) {

            /**
             * Comparing timeslot and from datetime weekdays
             */
            if ($schedule->datetime_from->weekday() == $timeslot->datetime_from->weekday()) {

                /**
                 * Adding timeslot to response
                 */
                $response->add(
                    $timeslot
                );
            }
        }

        return $response;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFutureForVybe(
        Vybe $vybe
    ) : Collection
    {
        /**
         * Checking vybe type
         */
        if (!$vybe->getType()->isSolo()) {

            /**
             * Returning timeslots
             */
            return $this->timeslotRepository->getFutureForVybe(
                $vybe
            );
        }

        return new Collection();
    }
}
