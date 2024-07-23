<?php

namespace App\Repositories\Cart;

use App\Exceptions\DatabaseException;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\CartItem;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Cart\Interfaces\CartItemRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class CartItemRepository
 *
 * @package App\Repositories\Cart
 */
class CartItemRepository extends BaseRepository implements CartItemRepositoryInterface
{
    /**
     * CartItemRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.cartItem.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return CartItem|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?CartItem
    {
        try {
            return CartItem::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return CartItem|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?CartItem
    {
        try {
            return CartItem::query()
                ->with([
                    'user',
                    'appearanceCase'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return CartItem::query()
                ->with([
                    'user',
                    'appearanceCase'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return CartItem::query()
                ->with([
                    'user',
                    'appearanceCase'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByUser(
        User $user
    ) : Collection
    {
        try {
            return CartItem::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'avatar_id',
                            'state_status_id',
                            'label_id',
                            'account_status_id',
                            'gender_id',
                            'username'
                        ])->withCount([
                            'vybes'
                        ]);
                    },
                    'appearanceCase.vybe',
                    'appearanceCase.vybe.user',
                    'appearanceCase.vybe.activity',
                    'appearanceCase.vybe.appearanceCases',
                    'appearanceCase.vybe.appearanceCases.unit',
                    'appearanceCase.unit',
                    'timeslot.users' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'avatar_id',
                            'state_status_id',
                            'label_id',
                            'account_status_id',
                            'gender_id',
                            'username'
                        ])->withCount([
                            'vybes'
                        ]);
                    }
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param AppearanceCase $appearanceCase
     * @param Timeslot|null $timeslot
     * @param string $datetimeFrom
     * @param string $datetimeTo
     * @param int $quantity
     *
     * @return CartItem|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        AppearanceCase $appearanceCase,
        ?Timeslot $timeslot,
        string $datetimeFrom,
        string $datetimeTo,
        int $quantity
    ) : ?CartItem
    {
        try {
            return CartItem::query()->create([
                'user_id'            => $user->id,
                'appearance_case_id' => $appearanceCase->id,
                'timeslot_id'        => $timeslot?->id,
                'datetime_from'      => Carbon::parse($datetimeFrom)->format('Y-m-d H:i:s'),
                'datetime_to'        => Carbon::parse($datetimeTo)->format('Y-m-d H:i:s'),
                'quantity'           => $quantity
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CartItem $cartItem
     * @param User|null $user
     * @param AppearanceCase|null $appearanceCase
     * @param Timeslot|null $timeslot
     * @param string|null $datetimeFrom
     * @param string|null $datetimeTo
     * @param int|null $quantity
     *
     * @return CartItem
     *
     * @throws DatabaseException
     */
    public function update(
        CartItem $cartItem,
        ?User $user,
        ?AppearanceCase $appearanceCase,
        ?Timeslot $timeslot,
        ?string $datetimeFrom,
        ?string $datetimeTo,
        ?int $quantity
    ) : CartItem
    {
        try {
            $cartItem->update([
                'user_id'            => $user ? $user->id : $cartItem->user_id,
                'appearance_case_id' => $appearanceCase ? $appearanceCase->id : $cartItem->appearance_case_id,
                'timeslot_id'        => $timeslot ? $timeslot->id : $cartItem->timeslot_id,
                'datetime_from'      => $datetimeFrom ? Carbon::parse($datetimeFrom)->format('Y-m-d H:i:s') : $cartItem->datetime_from,
                'datetime_to'        => $datetimeTo ? Carbon::parse($datetimeTo)->format('Y-m-d H:i:s') : $cartItem->datetime_to,
                'quantity'           => $quantity ?: $cartItem->quantity
            ]);

            return $cartItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CartItem $cartItem
     * @param int $quantity
     *
     * @return CartItem
     *
     * @throws DatabaseException
     */
    public function updateQuantity(
        CartItem $cartItem,
        int $quantity
    ) : CartItem
    {
        try {
            $cartItem->update([
                'quantity' => $quantity ?: $cartItem->quantity
            ]);

            return $cartItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CartItem $cartItem
     * @param Timeslot $timeslot
     *
     * @return CartItem
     *
     * @throws DatabaseException
     */
    public function updateTimeslot(
        CartItem $cartItem,
        Timeslot $timeslot
    ) : CartItem
    {
        try {
            $cartItem->update([
                'timeslot_id' => $timeslot->id
            ]);

            return $cartItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForUser(
        User $user
    ) : bool
    {
        try {
            return CartItem::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CartItem $cartItem
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        CartItem $cartItem
    ) : bool
    {
        try {
            return $cartItem->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/cart/cartItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
