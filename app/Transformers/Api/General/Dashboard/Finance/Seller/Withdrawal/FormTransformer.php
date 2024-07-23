<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal
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
        'withdrawal_receipt_statuses',
        'request_statuses'
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
        $paymentMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        return $this->collection($paymentMethods, new PaymentMethodTransformer);
    }

    /**
     * @return Collection
     */
    public function includeWithdrawalReceiptStatuses() : Collection
    {
        $withdrawalReceiptStatuses = WithdrawalReceiptStatusList::getItems();

        return $this->collection($withdrawalReceiptStatuses, new WithdrawalReceiptStatusTransformer);
    }

    /**
     * @return Collection
     */
    public function includeRequestStatuses() : Collection
    {
        $requestStatuses = RequestStatusList::getItems();

        return $this->collection($requestStatuses, new RequestStatusTransformer);
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
