<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Models\MySql\Media\VatNumberProofDocument;
use App\Transformers\BaseTransformer;

/**
 * Class VatNumberProofDocumentTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
 */
class VatNumberProofDocumentTransformer extends BaseTransformer
{
    /**
     * @param VatNumberProofDocument $vatNumberProofDocument
     *
     * @return array
     */
    public function transform(VatNumberProofDocument $vatNumberProofDocument) : array
    {
        return [
            'id'   => $vatNumberProofDocument->id,
            'url'  => $vatNumberProofDocument->url,
            'mime' => $vatNumberProofDocument->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vat_number_proof_document';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vat_number_proof_documents';
    }
}
