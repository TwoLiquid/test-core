<?php

namespace App\Transformers\Api\Guest\Payment\Method\Withdrawal\Status;

use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentMethodWithdrawalStatusTransformer
 *
 * @package App\Transformers\Api\Guest\Payment\Method\Withdrawal\Status
 */
class PaymentMethodWithdrawalStatusTransformer extends BaseTransformer
{
    /**
     * @param PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem
     *
     * @return array
     */
    public function transform(PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem) : array
    {
        return [
            'id'   => $paymentMethodWithdrawalStatusListItem->id,
            'code' => $paymentMethodWithdrawalStatusListItem->code,
            'name' => $paymentMethodWithdrawalStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_withdrawal_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_withdrawal_statuses';
    }
}
