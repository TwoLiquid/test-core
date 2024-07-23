<?php

namespace App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest;

use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class PayoutMethodRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest
 */
class PayoutMethodRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'method',
        'language',
        'request_status',
        'admin'
    ];

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return array
     */
    public function transform(PayoutMethodRequest $payoutMethodRequest) : array
    {
        return [
            'id'         => $payoutMethodRequest->_id,
            'waiting'    => Carbon::now()->diff($payoutMethodRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $payoutMethodRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeUser(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $user = null;

        if ($payoutMethodRequest->relationLoaded('user')) {
            $user = $payoutMethodRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeMethod(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $paymentMethod = null;

        if ($payoutMethodRequest->relationLoaded('method')) {
            $paymentMethod = $payoutMethodRequest->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeAdmin(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $admin = null;

        if ($payoutMethodRequest->relationLoaded('admin')) {
            $admin = $payoutMethodRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeLanguage(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $language = $payoutMethodRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $requestStatus = $payoutMethodRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payout_method_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payout_method_requests';
    }
}
