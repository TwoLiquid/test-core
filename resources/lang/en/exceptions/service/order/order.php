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

    'checkOrderItemsAvailableForOrder' => [
        'vybeOwner'      => __('exceptions.service.order.order.checkOrderItemsAvailableForOrder.vybeOwner'),
        'timeslotIsBusy' => __('exceptions.service.order.order.checkOrderItemsAvailableForOrder.timeslotIsBusy'),
        'timeslotIsFull' => __('exceptions.service.order.order.checkOrderItemsAvailableForOrder.timeslotIsFull')
    ],
    'createOrder' => [
        'order' => __('exceptions.service.order.order.createOrder.order')
    ],
    'getOrderPaymentUrlByMethod' => [
        'wrongPayment' => __('exceptions.service.order.order.getOrderPaymentUrlByMethod.wrongPayment')
    ],
    'executePaymentByMethod' => [
        'credentials'      => __('exceptions.service.order.order.executePaymentByMethod.credentials'),
        'orderTransaction' => __('exceptions.service.order.order.executePaymentByMethod.orderTransaction'),
        'wrongPayment'     => __('exceptions.service.order.order.getOrderPaymentUrlByMethod.wrongPayment')
    ]
];