<?php

namespace App\Repositories\Order\Interfaces;

use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderTransaction;
use App\Models\MySql\Payment\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface OrderTransactionRepositoryInterface
 *
 * @package App\Repositories\Order\Interfaces
 */
interface OrderTransactionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return OrderTransaction|null
     */
    public function findById(
        ?int $id
    ) : ?OrderTransaction;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param OrderInvoice $orderInvoice
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     *
     * @return OrderTransaction|null
     */
    public function store(
        OrderInvoice $orderInvoice,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description
    ) : ?OrderTransaction;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderTransaction $orderTransaction
     * @param OrderInvoice|null $orderInvoice
     * @param PaymentMethod|null $paymentMethod
     * @param string|null $externalId
     * @param float|null $amount
     * @param float|null $transactionFee
     * @param string|null $description
     *
     * @return OrderTransaction
     */
    public function update(
        OrderTransaction $orderTransaction,
        ?OrderInvoice $orderInvoice,
        ?PaymentMethod $paymentMethod,
        ?string $externalId,
        ?float $amount,
        ?float $transactionFee,
        ?string $description
    ) : OrderTransaction;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param OrderTransaction $orderTransaction
     *
     * @return bool
     */
    public function delete(
        OrderTransaction $orderTransaction
    ) : bool;
}
