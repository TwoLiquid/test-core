<?php

namespace App\Microservices\Log\Responses;

/**
 * Class UserWalletTransactionLogResponse
 *
 * @property int $id
 * @property float $amount
 * @property float $balance
 * @property string $createdAt
 * @property string $template
 * @property array $data
 *
 * @package App\Microservices\Log\Responses
 */
class UserWalletTransactionLogResponse extends BaseResponse
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var float
     */
    public float $amount;

    /**
     * @var float
     */
    public float $balance;

    /**
     * @var string
     */
    public string $createdAt;

    /**
     * @var string
     */
    public string $template;

    /**
     * @var array|null
     */
    public ?array $data;

    /**
     * UserWalletTransactionLogResponse constructor
     *
     * @param object $response
     * @param string|null $message
     */
    public function __construct(
        object $response,
        ?string $message
    )
    {
        $this->id = $response->id;
        $this->amount = $response->amount;
        $this->balance = $response->balance;
        $this->createdAt = $response->created_at;
        $this->template = $response->template;
        $this->data = (array) $response->data;

        parent::__construct($message);
    }
}