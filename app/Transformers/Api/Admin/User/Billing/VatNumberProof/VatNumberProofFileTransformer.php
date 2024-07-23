<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Transformers\BaseTransformer;

/**
 * Class VatNumberProofFileTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
 */
class VatNumberProofFileTransformer extends BaseTransformer
{
    /**
     * @param $vatNumberProofFile
     *
     * @return array
     */
    public function transform($vatNumberProofFile) : array
    {
        return [
            'id'   => $vatNumberProofFile->id,
            'url'  => $vatNumberProofFile->url,
            'mime' => $vatNumberProofFile->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vat_number_proof_file';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vat_number_proof_files';
    }
}
