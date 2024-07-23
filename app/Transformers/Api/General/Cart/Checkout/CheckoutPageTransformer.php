<?php

namespace App\Transformers\Api\General\Cart\Checkout;

use App\Models\MySql\Order\Order;
use App\Transformers\BaseTransformer;

/**
 * Class CheckoutPageTransformer
 *
 * @package App\Transformers\Api\General\Cart\Checkout
 */
class CheckoutPageTransformer extends BaseTransformer
{
    /**
     * @var Order
     */
    protected Order $order;

    /**
     * @var string|null
     */
    protected ?string $hash;

    /**
     * @var string|null
     */
    protected ?string $paymentUrl;

    /**
     * CheckoutPageTransformer constructor
     *
     * @param Order $order
     * @param string|null $hash
     * @param string|null $paymentUrl
     */
    public function __construct(
        Order $order,
        ?string $hash = null,
        ?string $paymentUrl = null
    )
    {
        /** @var Order order */
        $this->order = $order;

        /** @var string hash */
        $this->hash = $hash;

        /** @var string paymentUrl */
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'order_id'    => $this->order->id,
            'hash'        => $this->hash,
            'payment_url' => $this->paymentUrl
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'checkout_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'checkout_pages';
    }
}
