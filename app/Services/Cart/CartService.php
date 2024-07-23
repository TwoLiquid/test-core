<?php

namespace App\Services\Cart;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Models\MySql\CartItem;
use App\Models\MySql\User\User;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Cart\CartItemRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Services\Cart\Interfaces\CartServiceInterface;
use App\Services\Schedule\ScheduleService;
use App\Services\Timeslot\TimeslotService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CartService
 *
 * @package App\Services\Cart
 */
class CartService implements CartServiceInterface
{
    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var CartItemRepository
     */
    protected CartItemRepository $cartItemRepository;

    /**
     * @var ScheduleService
     */
    protected ScheduleService $scheduleService;

    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * CartService constructor
     */
    public function __construct()
    {
        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var CartItemRepository cartItemRepository */
        $this->cartItemRepository = new CartItemRepository();

        /** @var ScheduleService scheduleService */
        $this->scheduleService = new ScheduleService();

        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getRefreshedItems(
        User $user
    ) : Collection
    {
        /**
         * Getting cart items
         */
        $cartItems = $this->cartItemRepository->getByUser(
            $user
        );

        /**
         * Preparing a response cart items collection
         */
        $responseCartItems = new Collection();

        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {

            /**
             * Getting timeslot
             */
            $timeslot = $this->timeslotRepository->findForVybeByDates(
                $cartItem->appearanceCase->vybe,
                $cartItem['datetime_from'],
                $cartItem['datetime_to']
            );

            /**
             * Checking timeslot existence
             */
            if ($timeslot) {

                /**
                 * Updating cart item
                 */
                $cartItem = $this->cartItemRepository->updateTimeslot(
                    $cartItem,
                    $timeslot
                );
            }

            /**
             * Adding cart item to a response collection
             */
            $responseCartItems->push(
                $cartItem
            );
        }

        return $responseCartItems;
    }

    /**
     * @param User $user
     * @param array $cartItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function refresh(
        User $user,
        array $cartItems
    ) : Collection
    {
        /**
         * Preparing a response cart items collection
         */
        $responseCartItems = new Collection();

        /**
         * Deleting all user cart items
         */
        $this->cartItemRepository->deleteForUser(
            $user
        );

        /**
         * Getting user offset
         */
        $offset = $user->timezone
            ->getCurrentOffset()
            ->offset;

        /** @var array $cartItem */
        foreach ($cartItems as $cartItem) {

            /**
             * Getting date time from with offset
             */
            $dateTimeFrom = Carbon::parse($cartItem['datetime_from'])->addSeconds(
                -$offset
            );

            /**
             * Getting date time to with offset
             */
            $dateTimeTo = Carbon::parse($cartItem['datetime_to'])->addSeconds(
                -$offset
            );

            /**
             * Getting an appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->findById(
                $cartItem['appearance_case_id']
            );

            /**
             * Getting timeslot
             */
            $timeslot = $this->timeslotRepository->findForVybeByDates(
                $appearanceCase->vybe,
                $dateTimeFrom,
                $dateTimeTo
            );

            /**
             * Creating cart item
             */
            $cartItem = $this->cartItemRepository->store(
                $user,
                $appearanceCase,
                $timeslot,
                $dateTimeFrom->format('Y-m-d\TH:i:s.v\Z'),
                $dateTimeTo->format('Y-m-d\TH:i:s.v\Z'),
                $cartItem['quantity']
            );

            /**
             * Adding cart item to a response collection
             */
            $responseCartItems->push(
                $cartItem
            );
        }

        return $responseCartItems;
    }

    /**
     * @param User $user
     * @param array $cartItems
     *
     * @return bool
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function checkTimeslotAvailability(
        User $user,
        array $cartItems
    ) : bool
    {
        /**
         * @var int $key
         * @var array $cartItems
         */
        foreach ($cartItems as $key => $cartItem) {

            /**
             * Getting an appearance case
             */
            $appearanceCase = $this->appearanceCaseRepository->findById(
                $cartItem['appearance_case_id']
            );

            try {

                /**
                 * Checking schedules
                 */
                if (!$this->scheduleService->isAccessible(
                    $appearanceCase->vybe,
                    Carbon::parse($cartItem['datetime_from']),
                    Carbon::parse($cartItem['datetime_to'])
                )) {
                    throw new ValidationException(
                        'Cart item not match with vybe schedules.',
                        'cart_items.' . $key
                    );
                }

                /**
                 * Checking timeslot is available
                 */
                $this->timeslotService->isAvailable(
                    $user,
                    $appearanceCase,
                    Carbon::parse($cartItem['datetime_from']),
                    Carbon::parse($cartItem['datetime_to'])
                );
            } catch (BaseException $exception) {
                throw new ValidationException(
                    $exception->getHumanReadableMessage(),
                    'cart_items.' . $key
                );
            }
        }

        return true;
    }
}
