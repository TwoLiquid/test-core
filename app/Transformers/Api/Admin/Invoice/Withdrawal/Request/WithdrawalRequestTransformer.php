<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Request;

use App\Models\MongoDb\WithdrawalRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Request
 */
class WithdrawalRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'method',
        'status'
    ];

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return array
     */
    public function transform(WithdrawalRequest $withdrawalRequest) : array
    {
        return [
            'id'         => $withdrawalRequest->_id,
            'amount'     => $withdrawalRequest->amount,
            'created_at' => $withdrawalRequest->created_at ?
                $withdrawalRequest->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'toast_message_text' => $withdrawalRequest->toast_message_text ?
                $withdrawalRequest->toast_message_text :
                null
        ];
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeUser(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $user = $withdrawalRequest->user;

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeMethod(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $payoutMethod = $withdrawalRequest->method;

        return $payoutMethod ? $this->item($payoutMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeStatus(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $status = $withdrawalRequest->getStatus();

        return $status ? $this->item($status, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_requests';
    }
}
