<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Models\MySql\Media\WithdrawalReceiptProofDocument;
use App\Transformers\BaseTransformer;

/**
 * Class WithdrawalReceiptProofDocumentTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalReceiptProofDocumentTransformer extends BaseTransformer
{
    /**
     * @param WithdrawalReceiptProofDocument $withdrawalReceiptProofDocument
     *
     * @return array
     */
    public function transform(WithdrawalReceiptProofDocument $withdrawalReceiptProofDocument) : array
    {
        return [
            'id'   => $withdrawalReceiptProofDocument->id,
            'url'  => $withdrawalReceiptProofDocument->url,
            'mime' => $withdrawalReceiptProofDocument->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt_proof_document';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipt_proof_documents';
    }
}
