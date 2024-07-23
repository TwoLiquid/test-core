<?php

namespace App\Transformers\Api\General\Setting\Payout\Method;

use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class PaymentMethodWithdrawalStatusTransformer
 *
 * @package App\Transformers\Api\General\Setting\Payout\Method
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
            'name' => $paymentMethodWithdrawalStatusListItem->name,
            'code' => $paymentMethodWithdrawalStatusListItem->code
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
