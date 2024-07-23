<?php

namespace App\Repositories\Tip\Interfaces;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TipTransactionRepositoryInterface
 *
 * @package App\Repositories\Tip\Interfaces
 */
interface TipTransactionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TipTransaction|null
     */
    public function findById(
        ?int $id
    ) : ?TipTransaction;

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
     * @param Tip $tip
     * @param PaymentMethod $paymentMethod
     * @param string|null $externalId
     * @param float $amount
     * @param float|null $transactionFee
     * @param string|null $description
     * @param string|null $createdAt
     *
     * @return TipTransaction|null
     */
    public function store(
        Tip $tip,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt
    ) : ?TipTransaction;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
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
     */
    public function update(
        TipTransaction $tipTransaction,
        Tip $tip,
        PaymentMethod $paymentMethod,
        ?string $externalId,
        float $amount,
        ?float $transactionFee,
        ?string $description,
        ?string $createdAt
    ) : TipTransaction;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TipTransaction $tipTransaction
     *
     * @return bool
     */
    public function delete(
        TipTransaction $tipTransaction
    ) : bool;
}
