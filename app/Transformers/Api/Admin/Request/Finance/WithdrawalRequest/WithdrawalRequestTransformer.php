<?php

namespace App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest;

use App\Models\MongoDb\WithdrawalRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest
 */
class WithdrawalRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'method',
        'language',
        'request_status',
        'payout_status',
        'admin'
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
            'waiting'    => Carbon::now()->diff($withdrawalRequest->created_at)->format('%H:%I:%S'),
            'amount'     => $withdrawalRequest->amount,
            'created_at' => $withdrawalRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeUser(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $user = null;

        if ($withdrawalRequest->relationLoaded('user')) {
            $user = $withdrawalRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeMethod(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $method = null;

        if ($withdrawalRequest->relationLoaded('method')) {
            $method = $withdrawalRequest->method;
        }

        return $method ? $this->item($method, new PaymentMethodTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeAdmin(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $admin = null;

        if ($withdrawalRequest->relationLoaded('admin')) {
            $admin = $withdrawalRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeLanguage(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $language = $withdrawalRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $requestStatus = $withdrawalRequest->getStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return Item|null
     */
    public function includePayoutStatus(WithdrawalRequest $withdrawalRequest) : ?Item
    {
        $receiptStatus = null;

        if ($withdrawalRequest->relationLoaded('receipt')) {
            if ($withdrawalRequest->receipt) {
                $receiptStatus = $withdrawalRequest->receipt
                    ->getStatus();
            }
        }

        return $receiptStatus ? $this->item($receiptStatus, new WithdrawalReceiptStatusTransformer) : null;
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
