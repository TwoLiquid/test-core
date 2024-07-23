<?php

namespace App\Repositories\Payout\Interfaces;

use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MongoDb\Payout\PayoutMethodRequestField;
use App\Models\MySql\Payment\PaymentMethodField;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PayoutMethodRequestFieldRepositoryInterface
 *
 * @package App\Repositories\Payout\Interfaces
 */
interface PayoutMethodRequestFieldRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return PayoutMethodRequestField|null
     */
    public function findById(
        ?string $id
    ) : ?PayoutMethodRequestField;

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
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides checking row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Collection
     */
    public function getForRequest(
        PayoutMethodRequest $payoutMethodRequest
    ) : Collection;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param PaymentMethodField $paymentMethodField
     * @param $value
     *
     * @return PayoutMethodRequestField|null
     */
    public function store(
        PayoutMethodRequest $payoutMethodRequest,
        PaymentMethodField $paymentMethodField,
        $value
    ) : ?PayoutMethodRequestField;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PayoutMethodRequestField $payoutMethodRequestField
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param PaymentMethodField $paymentMethodField
     * @param $value
     *
     * @return PayoutMethodRequestField
     */
    public function update(
        PayoutMethodRequestField $payoutMethodRequestField,
        PayoutMethodRequest $payoutMethodRequest,
        PaymentMethodField $paymentMethodField,
        $value
    ) : PayoutMethodRequestField;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PayoutMethodRequestField $payoutMethodRequestField
     *
     * @return bool
     */
    public function delete(
        PayoutMethodRequestField $payoutMethodRequestField
    ) : bool;
}
