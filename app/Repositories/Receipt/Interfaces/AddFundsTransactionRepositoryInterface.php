<?php

namespace App\Repositories\Receipt\Interfaces;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\Transaction\AddFundsTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface OrderTransactionRepositoryInterface
 *
 * @package App\Repositories\Order\Interfaces
 */
interface AddFundsTransactionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AddFundsTransaction|null
     */
    public function findById(
        ?int $id
    ) : ?AddFundsTransaction;

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
     * @param AddFundsReceipt $addFundsReceipt
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return AddFundsTransaction|null
     */
    public function store(
        AddFundsReceipt $addFundsReceipt,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt
    ) : ?AddFundsTransaction;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AddFundsTransaction $addFundsTransaction
     * @param AddFundsReceipt $addFundsReceipt
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float|null $amount
     * @param float|null $transactionFee
     * @param string|null $description
     *
     * @return AddFundsTransaction
     */
    public function update(
        AddFundsTransaction $addFundsTransaction,
        AddFundsReceipt $addFundsReceipt,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        ?float $amount,
        ?float $transactionFee,
        ?string $description
    ) : AddFundsTransaction;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AddFundsTransaction $addFundsTransaction
     *
     * @return bool
     */
    public function delete(
        AddFundsTransaction $addFundsTransaction
    ) : bool;
}
