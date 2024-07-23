<?php

namespace App\Transformers\Api\Admin\User\Payout\Method\Request;

use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MongoDb\Payout\PayoutMethodRequestField;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PayoutMethodRequestTransformer
 *
 * @package App\Transformers\Api\Admin\User\Payout\Method\Request
 */
class PayoutMethodRequestTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PaymentMethodTransformer constructor
     *
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'method',
        'fields',
        'admin',
        'request_status',
        'toast_message_type'
    ];

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return array
     */
    public function transform(PayoutMethodRequest $payoutMethodRequest) : array
    {
        return [
            'id'                 => $payoutMethodRequest->_id,
            'toast_message_text' => $payoutMethodRequest->toast_message_text
        ];
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeUser(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $user = $payoutMethodRequest->user;

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeMethod(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $method = $payoutMethodRequest->method;

        return $method ?
            $this->item(
                $method,
                new PaymentMethodTransformer(
                    $payoutMethodRequest->user,
                    $this->paymentMethodImages
                )
            ) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Collection|null
     */
    public function includeFields(PayoutMethodRequest $payoutMethodRequest) : ?Collection
    {
        $payoutMethodRequestFields = new EloquentCollection();

        if ($payoutMethodRequest->relationLoaded('fields')) {

            /** @var PayoutMethodRequestField $payoutMethodRequestField */
            foreach ($payoutMethodRequest->fields as $payoutMethodRequestField) {
                if ($payoutMethodRequestField->field) {
                    $payoutMethodRequestFields->add(
                        $payoutMethodRequestField
                    );
                }
            }
        }

        return $this->collection($payoutMethodRequestFields, new PayoutMethodRequestFieldTransformer);
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $toastMessageType = $payoutMethodRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Item|null
     */
    public function includeAdmin(PayoutMethodRequest $payoutMethodRequest) : ?Item
    {
        $admin = $payoutMethodRequest->admin;

        return $admin ? $this->item($admin, new AdminTransformer) : null;
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
