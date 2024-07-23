<?php

namespace App\Repositories\Payment\Interfaces;

use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Payment\PaymentMethodField;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PaymentMethodFieldRepositoryInterface
 *
 * @package App\Repositories\Payment\Interfaces
 */
interface PaymentMethodFieldRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PaymentMethodField|null
     */
    public function findById(
        ?int $id
    ) : ?PaymentMethodField;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return Collection
     */
    public function getAllByMethod(
        PaymentMethod $paymentMethod
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param PaymentMethod $paymentMethod
     * @param PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem
     * @param array $title
     * @param array $placeholder
     * @param array|null $tooltip
     *
     * @return PaymentMethodField|null
     */
    public function store(
        PaymentMethod $paymentMethod,
        PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem,
        array $title,
        array $placeholder,
        ?array $tooltip
    ) : ?PaymentMethodField;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param PaymentMethodField $paymentMethodField
     * @param PaymentMethod|null $paymentMethod
     * @param PaymentMethodFieldTypeListItem|null $paymentMethodFieldTypeListItem
     * @param array|null $title
     * @param array|null $placeholder
     * @param array|null $tooltip
     *
     * @return PaymentMethodField
     */
    public function update(
        PaymentMethodField $paymentMethodField,
        ?PaymentMethod $paymentMethod,
        ?PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem,
        ?array $title,
        ?array $placeholder,
        ?array $tooltip
    ) : PaymentMethodField;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PaymentMethodField $paymentMethodField
     *
     * @return bool
     */
    public function delete(
        PaymentMethodField $paymentMethodField
    ) : bool;
}
