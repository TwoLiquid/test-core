<?php

namespace App\Support\Service\PayPal;

use Drewdan\Paypal\Services\Orders\Order as PayPalOrder;

/**
 * Class OrderResponse
 *
 * @package App\Support\Service\PayPal
 */
class OrderResponse
{
    /**
     * @var PayPalOrder
     */
    public PayPalOrder $payPalOrder;

    /**
     * @var string
     */
    public string $paymentUrl;

    /**
     * OrderResponse constructor
     *
     * @param PayPalOrder $payPalOrder
     * @param string $paymentUrl
     */
    public function __construct(
        PayPalOrder $payPalOrder,
        string $paymentUrl
    )
    {
        /** @var PayPalOrder payPalOrder */
        $this->payPalOrder = $payPalOrder;

        /** @var string paymentUrl */
        $this->paymentUrl = $paymentUrl;
    }
}
