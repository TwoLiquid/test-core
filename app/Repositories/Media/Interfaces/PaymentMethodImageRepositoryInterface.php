<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Media\PaymentMethodImage;
use App\Models\MySql\Payment\PaymentMethod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PaymentMethodImageRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface PaymentMethodImageRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PaymentMethodImage|null
     */
    public function findById(
        ?int $id
    ) : ?PaymentMethodImage;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return PaymentMethodImage|null
     */
    public function findByPaymentMethod(
        PaymentMethod $paymentMethod
    ) : ?PaymentMethodImage;

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
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param Collection $paymentMethods
     *
     * @return Collection
     */
    public function getByPaymentMethods(
        Collection $paymentMethods
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;
}
