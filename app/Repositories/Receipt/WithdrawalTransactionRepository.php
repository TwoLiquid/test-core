<?php

namespace App\Repositories\Receipt;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\Transaction\WithdrawalTransaction;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Repositories\BaseRepository;
use App\Repositories\Receipt\Interfaces\WithdrawalTransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class WithdrawalTransactionRepository
 *
 * @package App\Repositories\Receipt
 */
class WithdrawalTransactionRepository extends BaseRepository implements WithdrawalTransactionRepositoryInterface
{
    /**
     * WithdrawalTransactionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.withdrawalTransaction.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return WithdrawalTransaction|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?WithdrawalTransaction
    {
        try {
            return WithdrawalTransaction::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/withdrawalTransaction.' . __FUNCTION__),
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
            return WithdrawalTransaction::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/withdrawalTransaction.' . __FUNCTION__),
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
            return WithdrawalTransaction::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/withdrawalTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param PaymentMethod $payoutMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return WithdrawalTransaction|null
     *
     * @throws DatabaseException
     */
    public function store(
        WithdrawalReceipt $withdrawalReceipt,
        PaymentMethod $payoutMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt = null
    ) : ?WithdrawalTransaction
    {
        try {
            return WithdrawalTransaction::query()->create([
                'receipt_id'      => $withdrawalReceipt->id,
                'method_id'       => $payoutMethod->id,
                'external_id'     => $externalId,
                'amount'          => $amount,
                'transaction_fee' => $transactionFee,
                'description'     => $description,
                'created_at'      => $createdAt ?
                    Carbon::parse($createdAt)->format('Y-m-d H:i:s') :
                    Carbon::now()->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/withdrawalTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalTransaction $withdrawalTransaction
     * @param WithdrawalReceipt|null $withdrawalReceipt
     * @param PaymentMethod|null $payoutMethod
     * @param string|null $externalId
     * @param float|null $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return WithdrawalTransaction
     *
     * @throws DatabaseException
     */
    public function update(
        WithdrawalTransaction $withdrawalTransaction,
        ?WithdrawalReceipt $withdrawalReceipt,
        ?PaymentMethod $payoutMethod,
        ?string $externalId,
        ?float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt = null
    ) : WithdrawalTransaction
    {
        try {
            $withdrawalTransaction->update([
                'receipt_id'      => $withdrawalReceipt ? $withdrawalReceipt->id : $withdrawalTransaction->receipt_id,
                'method_id'       => $payoutMethod ? $payoutMethod->id : $withdrawalTransaction->method_id,
                'external_id'     => $externalId ?: $withdrawalTransaction->external_id,
                'amount'          => $amount ?: $withdrawalTransaction->amount,
                'transaction_fee' => $transactionFee ?: $withdrawalTransaction->transaction_fee,
                'description'     => $description ?: $withdrawalTransaction->description,
                'created_at'      => $createdAt ?
                    Carbon::parse($createdAt)->format('Y-m-d H:i:s') :
                    $withdrawalTransaction->created_at
            ]);

            return $withdrawalTransaction;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/withdrawalTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalTransaction $withdrawalTransaction
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        WithdrawalTransaction $withdrawalTransaction
    ) : bool
    {
        try {
            return $withdrawalTransaction->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/withdrawalTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}