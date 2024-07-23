<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds;

use App\Exceptions\DatabaseException;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * FormTransformer constructor
     */
    public function __construct()
    {
        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payment_methods',
        'add_funds_receipt_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function includePaymentMethods() : Collection
    {
        $paymentMethods = $this->paymentMethodRepository->getAllPaymentIntegrated();

        return $this->collection($paymentMethods, new PaymentMethodTransformer);
    }

    /**
     * @return Collection
     */
    public function includeAddFundsReceiptStatuses() : Collection
    {
        $addFundsReceiptStatuses = AddFundsReceiptStatusList::getItems();

        return $this->collection($addFundsReceiptStatuses, new AddFundsReceiptStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
