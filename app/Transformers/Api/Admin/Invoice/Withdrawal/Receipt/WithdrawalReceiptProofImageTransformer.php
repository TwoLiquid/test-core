<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Models\MySql\Media\WithdrawalReceiptProofImage;
use App\Transformers\BaseTransformer;

/**
 * Class WithdrawalReceiptProofImageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalReceiptProofImageTransformer extends BaseTransformer
{
    /**
     * @param WithdrawalReceiptProofImage $withdrawalReceiptProofImage
     *
     * @return array
     */
    public function transform(WithdrawalReceiptProofImage $withdrawalReceiptProofImage) : array
    {
        return [
            'id'      => $withdrawalReceiptProofImage->id,
            'url'     => $withdrawalReceiptProofImage->url,
            'url_min' => $withdrawalReceiptProofImage->url_min,
            'mime'    => $withdrawalReceiptProofImage->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt_proof_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipt_proof_images';
    }
}
