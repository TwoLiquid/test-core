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

    'getAddFundsPaymentUrlByMethod' => [
        'wrongPayment' => __('exceptions.service.addFunds.addFundsReceipt.getAddFundsPaymentUrlByMethod.wrongPayment')
    ],
    'executePaymentByMethod' => [
        'credentials'         => __('exceptions.service.addFunds.addFundsReceipt.executePaymentByMethod.credentials'),
        'addFundsTransaction' => __('exceptions.service.addFunds.addFundsReceipt.executePaymentByMethod.addFundsTransaction'),
        'wrongPayment'        => __('exceptions.service.addFunds.addFundsReceipt.getAddFundsPaymentUrlByMethod.wrongPayment')
    ]
];