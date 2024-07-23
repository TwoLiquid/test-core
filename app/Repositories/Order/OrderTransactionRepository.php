<?php

namespace App\Repositories\Order;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderTransaction;
use App\Models\MySql\Payment\PaymentMethod;
use App\Repositories\BaseRepository;
use App\Repositories\Order\Interfaces\OrderTransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class OrderTransactionRepository
 *
 * @package App\Repositories\Order
 */
class OrderTransactionRepository extends BaseRepository implements OrderTransactionRepositoryInterface
{
    /**
     * OrderTransactionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.orderTransaction.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return OrderTransaction|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?OrderTransaction
    {
        try {
            return OrderTransaction::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderTransaction.' . __FUNCTION__),
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
            return OrderTransaction::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderTransaction.' . __FUNCTION__),
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
            return OrderTransaction::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     *
     * @return OrderTransaction|null
     *
     * @throws DatabaseException
     */
    public function store(
        OrderInvoice $orderInvoice,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description
    ) : ?OrderTransaction
    {
        try {
            return OrderTransaction::query()->create([
                'invoice_id'      => $orderInvoice->id,
                'method_id'       => $paymentMethod->id,
                'external_id'     => $externalId,
                'amount'          => $amount,
                'transaction_fee' => $transactionFee,
                'description'     => $description
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderTransaction $orderTransaction
     * @param OrderInvoice|null $orderInvoice
     * @param PaymentMethod|null $paymentMethod
     * @param string|null $externalId
     * @param float|null $amount
     * @param float|null $transactionFee
     * @param string|null $description
     *
     * @return OrderTransaction
     *
     * @throws DatabaseException
     */
    public function update(
        OrderTransaction $orderTransaction,
        ?OrderInvoice $orderInvoice,
        ?PaymentMethod $paymentMethod,
        ?string $externalId,
        ?float $amount,
        ?float $transactionFee,
        ?string $description
    ) : OrderTransaction
    {
        try {
            $orderTransaction->update([
                'invoice_id'      => $orderInvoice ? $orderInvoice->id : $orderTransaction->invoice_id,
                'method_id'       => $paymentMethod ? $paymentMethod->id : $orderTransaction->method_id,
                'external_id'     => $externalId ?: $orderTransaction->external_id,
                'amount'          => $amount ?: $orderTransaction->amount,
                'transaction_fee' => $transactionFee ?: $orderTransaction->transaction_fee,
                'description'     => $description ?: $orderTransaction->description
            ]);

            return $orderTransaction;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderTransaction $orderTransaction
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        OrderTransaction $orderTransaction
    ) : bool
    {
        try {
            return $orderTransaction->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderTransaction.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
