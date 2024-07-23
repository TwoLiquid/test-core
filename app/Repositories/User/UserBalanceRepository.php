<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Lists\User\Balance\Status\UserBalanceStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserBalance;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserBalanceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserBalanceRepository
 *
 * @package App\Repositories\User
 */
class UserBalanceRepository extends BaseRepository implements UserBalanceRepositoryInterface
{
    /**
     * UserBalanceRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userBalance.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserBalance|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserBalance
    {
        try {
            return UserBalance::query()
                ->with([
                    'user'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
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
            return UserBalance::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
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
            return UserBalance::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function getByIdsTypesCount(
        array $ids
    ) : UserBalance
    {
        try {
            return UserBalance::query()
                ->whereIn('id', $ids)
                ->selectRaw('sum(case when type_id = 1 and status_id = 2 then 1 else 0 end) as buyer')
                ->selectRaw('sum(case when type_id = 2 and status_id = 2 then 1 else 0 end) as seller')
                ->selectRaw('sum(case when type_id = 3 and status_id = 2 then 1 else 0 end) as affiliate')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/user.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param UserBalanceStatusListItem $userBalanceStatusListItem
     *
     * @return UserBalance|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        UserBalanceStatusListItem $userBalanceStatusListItem
    ) : ?UserBalance
    {
        try {
            return UserBalance::query()->create([
                'user_id'        => $user->id,
                'type_id'        => $userBalanceTypeListItem->id,
                'status_id'      => $userBalanceStatusListItem->id,
                'amount'         => 0,
                'pending_amount' => !$userBalanceTypeListItem->isBuyer() ? 0 : null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     * @param User|null $user
     * @param UserBalanceTypeListItem|null $userBalanceTypeListItem
     * @param UserBalanceStatusListItem|null $userBalanceStatusListItem
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function update(
        UserBalance $userBalance,
        ?User $user,
        ?UserBalanceTypeListItem $userBalanceTypeListItem,
        ?UserBalanceStatusListItem $userBalanceStatusListItem
    ) : UserBalance
    {
        try {
            $userBalance->update([
                'user_id'   => $user ? $user->id : $userBalance->user_id,
                'type_id'   => $userBalanceTypeListItem ? $userBalanceTypeListItem->id : $userBalance->type_id,
                'status_id' => $userBalanceStatusListItem ? $userBalanceStatusListItem->id : $userBalance->status_id
            ]);

            return $userBalance;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     * @param UserBalanceStatusListItem $userBalanceStatusListItem
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        UserBalance $userBalance,
        UserBalanceStatusListItem $userBalanceStatusListItem
    ) : UserBalance
    {
        try {
            $userBalance->update([
                'status_id' => $userBalanceStatusListItem->id
            ]);

            return $userBalance;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function increaseAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance
    {
        try {
            $userBalance->update([
                'amount' => round(
                    $userBalance->amount + $amount,
                    2
                )
            ]);

            return $userBalance;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function increasePendingAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance
    {
        try {
            $userBalance->update([
                'pending_amount' => round(
                    $userBalance->pending_amount + $amount,
                    2
                )
            ]);

            return $userBalance;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function decreaseAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance
    {
        try {
            $userBalance->update([
                'amount' => round(
                    $userBalance->amount - $amount,
                    2
                )
            ]);

            return $userBalance;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     * @param float $amount
     *
     * @return UserBalance
     *
     * @throws DatabaseException
     */
    public function decreasePendingAmount(
        UserBalance $userBalance,
        float $amount
    ) : UserBalance
    {
        try {
            $userBalance->update([
                'pending_amount' => round(
                    $userBalance->pending_amount - $amount,
                    2
                )
            ]);

            return $userBalance;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserBalance $userBalance
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        UserBalance $userBalance
    ) : bool
    {
        try {
            return $userBalance->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userBalance.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
