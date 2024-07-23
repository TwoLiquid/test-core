<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Models\MySql\Media\VatNumberProofImage;
use App\Transformers\BaseTransformer;

/**
 * Class VatNumberProofImageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
 */
class VatNumberProofImageTransformer extends BaseTransformer
{
    /**
     * @param VatNumberProofImage $vatNumberProofImage
     *
     * @return array
     */
    public function transform(VatNumberProofImage $vatNumberProofImage) : array
    {
        return [
            'id'      => $vatNumberProofImage->id,
            'url'     => $vatNumberProofImage->url,
            'url_min' => $vatNumberProofImage->url_min,
            'mime'    => $vatNumberProofImage->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vat_number_proof_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vat_number_proof_images';
    }
}
