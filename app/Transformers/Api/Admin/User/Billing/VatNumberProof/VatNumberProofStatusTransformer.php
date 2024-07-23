<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Lists\VatNumberProof\Status\VatNumberProofStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class VatNumberProofStatusTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
 */
class VatNumberProofStatusTransformer extends BaseTransformer
{
    /**
     * @param VatNumberProofStatusListItem $vatNumberProofStatusListItem
     *
     * @return array
     */
    public function transform(VatNumberProofStatusListItem $vatNumberProofStatusListItem) : array
    {
        return [
            'id'   => $vatNumberProofStatusListItem->id,
            'code' => $vatNumberProofStatusListItem->code,
            'name' => $vatNumberProofStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vat_number_proof_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vat_number_proof_statuses';
    }
}