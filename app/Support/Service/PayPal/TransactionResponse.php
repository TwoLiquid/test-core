<?php

namespace App\Support\Service\PayPal;

/**
 * Class TransactionResponse
 *
 * @package App\Support\Service\PayPal
 */
class TransactionResponse
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var float
     */
    public float $transactionFee;

    /**
     * @var float
     */
    public float $totalAmount;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * TransactionResponse constructor
     *
     * @param string $id
     * @param float $transactionFee
     * @param float $totalAmount
     * @param string|null $description
     */
    public function __construct(
        string $id,
        float $transactionFee,
        float $totalAmount,
        ?string $description = null
    )
    {
        $this->id = $id;
        $this->transactionFee = $transactionFee;
        $this->totalAmount = $totalAmount;
        $this->description = $description;
    }
}
