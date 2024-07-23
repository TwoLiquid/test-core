<?php

namespace App\Repositories\Receipt\Interfaces;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\Transaction\WithdrawalTransaction;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface WithdrawalTransactionRepositoryInterface
 *
 * @package App\Repositories\Receipt\Interfaces
 */
interface WithdrawalTransactionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return WithdrawalTransaction|null
     */
    public function findById(
        ?int $id
    ) : ?WithdrawalTransaction;

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
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param PaymentMethod $payoutMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return WithdrawalTransaction|null
     */
    public function store(
        WithdrawalReceipt $withdrawalReceipt,
        PaymentMethod $payoutMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt
    ) : ?WithdrawalTransaction;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
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
     */
    public function update(
        WithdrawalTransaction $withdrawalTransaction,
        ?WithdrawalReceipt $withdrawalReceipt,
        ?PaymentMethod $payoutMethod,
        ?string $externalId,
        ?float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt
    ) : WithdrawalTransaction;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param WithdrawalTransaction $withdrawalTransaction
     *
     * @return bool
     */
    public function delete(
        WithdrawalTransaction $withdrawalTransaction
    ) : bool;
}
