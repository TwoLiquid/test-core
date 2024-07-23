<?php

namespace App\Transformers\Api\Admin\User\Payout\Method\Request;

use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class PaymentMethodTransformer
 *
 * @package App\Transformers\Api\Admin\User\Payout\Method\Request
 */
class PaymentMethodTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PaymentMethodTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        User $user,
        EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var User user */
        $this->user = $user;

        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'image',
        'payment_status',
        'withdrawal_status'
    ];

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return array
     */
    public function transform(PaymentMethod $paymentMethod) : array
    {
        return [
            'id'              => $paymentMethod->id,
            'code'            => $paymentMethod->code,
            'name'            => $paymentMethod->name,
            'payment_fee'     => $paymentMethod->payment_fee,
            'order_form'      => $paymentMethod->order_form,
            'display_name'    => $paymentMethod->display_name,
            'duration_title'  => $paymentMethod->duration_title,
            'duration_amount' => $paymentMethod->duration_amount,
            'fee_title'       => $paymentMethod->fee_title,
            'fee_amount'      => $paymentMethod->fee_amount,
        ];
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     */
    public function includeImage(PaymentMethod $paymentMethod) : ?Item
    {
        $paymentMethodImage = $this->paymentMethodImages?->filter(function ($item) use ($paymentMethod) {
            return $item->method_id == $paymentMethod->id;
        })->first();

        return $paymentMethodImage ? $this->item($paymentMethodImage, new PaymentMethodImageTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     */
    public function includePaymentStatus(PaymentMethod $paymentMethod) : ?Item
    {
        $paymentStatus = $paymentMethod->getPaymentStatus();

        return $paymentStatus ? $this->item($paymentStatus, new PaymentStatusTransformer) : null;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Item|null
     */
    public function includeWithdrawalStatus(PaymentMethod $paymentMethod) : ?Item
    {
        $withdrawalStatus = $paymentMethod->getWithdrawalStatus();

        return $withdrawalStatus ? $this->item($withdrawalStatus, new PaymentMethodWithdrawalStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_methods';
    }
}
