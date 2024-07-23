<?php

namespace App\Microservices\Media\Responses;

/**
 * Class WithdrawalReceiptProofImageResponse
 *
 * @property int $id
 * @property int $receiptId
 * @property string $url
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class WithdrawalReceiptProofImageResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $receiptId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $mime;

    /**
     * WithdrawalReceiptProofImageResponse constructor
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
        $this->receiptId = $response->receipt_id;
        $this->url = $response->url;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}