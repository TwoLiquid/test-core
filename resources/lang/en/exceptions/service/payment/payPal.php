<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Service Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the exception class
    |
    */

    'getPaymentUrl' => [
        'payment' => __('exceptions.service.payment.payPal.getPaymentUrl.payment'),
        'unknown' => __('exceptions.service.payment.payPal.getPaymentUrl.unknown')
    ],
    'executePayment' => [
        'parameters' => [
            'paymentId' => __('exceptions.service.payment.payPal.executePayment.parameters.paymentId'),
            'payerId'   => __('exceptions.service.payment.payPal.executePayment.parameters.payerId')
        ],
        'payment' => [
            'find'    => __('exceptions.service.payment.payPal.executePayment.payment.find'),
            'invoice' => __('exceptions.service.payment.payPal.executePayment.payment.invoice'),
            'state'   => __('exceptions.service.payment.payPal.executePayment.payment.state'),
            'execute' => __('exceptions.service.payment.payPal.executePayment.payment.execute')
        ]
    ]
];