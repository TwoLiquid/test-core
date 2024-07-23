<?php

namespace App\Repositories\Tip;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipTransaction;
use App\Repositories\BaseRepository;
use App\Repositories\Tip\Interfaces\TipTransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class TipTransactionRepository
 *
 * @package App\Repositories\Tip
 */
class TipTransactionRepository extends BaseRepository implements TipTransactionRepositoryInterface
{
    /**
     * TipTransactionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.tipTransaction.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return TipTransaction|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TipTransaction
    {
        try {
            return TipTransaction::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipTransaction.' . __FUNCTION__),
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
            return TipTransaction::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipTransaction.' . __FUNCTION__),
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
            return TipTransaction::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Tip $tip
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return TipTransaction|null
     *
     * @throws DatabaseException
     */
    public function store(
        Tip $tip,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt = null
    ) : ?TipTransaction
    {
        try {
            return TipTransaction::query()->create([
                'tip_id'          => $tip->id,
                'method_id'       => $paymentMethod->id,
                'external_id'     => $externalId,
                'amount'          => $amount,
                'transaction_fee' => $transactionFee,
                'description'     => $description,
                'created_at'      => $createdAt ?
                    Carbon::parse($createdAt)->format('Y-m-d H:i:s') :
                    null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipTransaction $tipTransaction
     * @param Tip $tip
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return TipTransaction
     *
     * @throws DatabaseException
     */
    public function update(
        TipTransaction $tipTransaction,
        Tip $tip,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt = null
    ) : TipTransaction
    {
        try {
            $tipTransaction->update([
                'tip_id'          => $tip->id,
                'method_id'       => $paymentMethod->id,
                'external_id'     => $externalId ?: $tipTransaction->external_id,
                'amount'          => $amount ?: $tipTransaction->amount,
                'transaction_fee' => $transactionFee ?: $tipTransaction->transaction_fee,
                'description'     => $description ?: $tipTransaction->description
            ]);

            return $tipTransaction;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipTransaction $tipTransaction
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TipTransaction $tipTransaction
    ) : bool
    {
        try {
            return $tipTransaction->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
