<?php

namespace App\Repositories\Payment\Interfaces;

use App\Models\MySql\Payment\PaymentHash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface PaymentHashRepositoryInterface
 *
 * @package App\Repositories\Payment\Interfaces
 */
interface PaymentHashRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PaymentHash|null
     */
    public function findById(
        ?int $id
    ) : ?PaymentHash;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $hash
     *
     * @return PaymentHash|null
     */
    public function findByHash(
        string $hash
    ) : ?PaymentHash;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param string $hash
     *
     * @return bool
     */
    public function existsForHash(
        string $hash
    ) : bool;

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
     * @param string $externalId
     * @param string $hash
     *
     * @return PaymentHash|null
     */
    public function store(
        string $externalId,
        string $hash
    ) : ?PaymentHash;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param PaymentHash $paymentHash
     * @param string $externalId
     * @param string $hash
     *
     * @return PaymentHash
     */
    public function update(
        PaymentHash $paymentHash,
        string $externalId,
        string $hash
    ) : PaymentHash;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PaymentHash $paymentHash
     *
     * @return bool
     */
    public function delete(
        PaymentHash $paymentHash
    ) : bool;
}
