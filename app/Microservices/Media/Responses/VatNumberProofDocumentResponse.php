<?php

namespace App\Microservices\Media\Responses;

use Carbon\Carbon;

/**
 * Class VatNumberProofDocumentResponse
 *
 * @property int $id
 * @property string $vatNumberProofId
 * @property string $url
 * @property string $mime
 * @property Carbon $createdAt
 *
 * @package App\Microservices\Media\Responses
 */
class VatNumberProofDocumentResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $vatNumberProofId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $mime;

    /**
     * @var Carbon
     */
    public Carbon $createdAt;

    /**
     * VatNumberProofDocumentResponse constructor
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
        $this->vatNumberProofId = $response->vat_number_proof_id;
        $this->url = $response->url;
        $this->mime = $response->mime;
        $this->createdAt = $response->created_at ?
            Carbon::parse($response->created_at) :
            null;

        parent::__construct($message);
    }
}