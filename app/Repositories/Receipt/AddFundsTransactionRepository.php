<?php

namespace App\Repositories\Receipt;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\Transaction\AddFundsTransaction;
use App\Repositories\BaseRepository;
use App\Repositories\Receipt\Interfaces\AddFundsTransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class AddFundsTransactionRepository
 *
 * @package App\Repositories\Receipt
 */
class AddFundsTransactionRepository extends BaseRepository implements AddFundsTransactionRepositoryInterface
{
    /**
     * AddFundsTransactionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.addFundsTransaction.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AddFundsTransaction|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AddFundsTransaction
    {
        try {
            return AddFundsTransaction::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/addFundsTransaction.' . __FUNCTION__),
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
            return AddFundsTransaction::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/addFundsTransaction.' . __FUNCTION__),
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
            return AddFundsTransaction::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/addFundsTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return AddFundsTransaction|null
     *
     * @throws DatabaseException
     */
    public function store(
        AddFundsReceipt $addFundsReceipt,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt = null
    ) : ?AddFundsTransaction
    {
        try {
            return AddFundsTransaction::query()->create([
                'receipt_id'      => $addFundsReceipt->id,
                'method_id'       => $paymentMethod->id,
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
                trans('exceptions/repository/receipt/transaction/addFundsTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsTransaction $addFundsTransaction
     * @param AddFundsReceipt $addFundsReceipt
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float|null $amount
     * @param float|null $transactionFee
     * @param string|null $description
     *
     * @return AddFundsTransaction
     *
     * @throws DatabaseException
     */
    public function update(
        AddFundsTransaction $addFundsTransaction,
        AddFundsReceipt $addFundsReceipt,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        ?float $amount,
        ?float $transactionFee,
        ?string $description
    ) : AddFundsTransaction
    {
        try {
            $addFundsTransaction->update([
                'receipt_id'      => $addFundsReceipt->id,
                'method_id'       => $paymentMethod->id,
                'external_id'     => $externalId ?: $addFundsTransaction->external_id,
                'amount'          => $amount ?: $addFundsTransaction->amount,
                'transaction_fee' => $transactionFee ?: $addFundsTransaction->transaction_fee,
                'description'     => $description ?: $addFundsTransaction->description
            ]);

            return $addFundsTransaction;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/addFundsTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsTransaction $addFundsTransaction
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        AddFundsTransaction $addFundsTransaction
    ) : bool
    {
        try {
            return $addFundsTransaction->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/transaction/addFundsTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
