<?php

namespace App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UploadProofFilesRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt
 */
class UploadProofFilesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'withdrawal_receipt_proof_files'                 => 'array|nullable',
            'withdrawal_receipt_proof_files.*.content'       => 'string|nullable',
            'withdrawal_receipt_proof_files.*.original_name' => 'string|nullable',
            'withdrawal_receipt_proof_files.*.extension'     => 'string|nullable',
            'withdrawal_receipt_proof_files.*.mime'          => 'string|nullable',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'withdrawal_receipt_proof_files.array'                  => trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.withdrawal_receipt_proof_files.array'),
            'withdrawal_receipt_proof_files.*.content.string'       => trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.withdrawal_receipt_proof_files.*.content.string'),
            'withdrawal_receipt_proof_files.*.original_name.string' => trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.withdrawal_receipt_proof_files.*.original_name.string'),
            'withdrawal_receipt_proof_files.*.mime.string'          => trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.withdrawal_receipt_proof_files.*.mime.string'),
            'withdrawal_receipt_proof_files.*.extension.string'     => trans('validations/api/admin/invoice/withdrawal/receipt/uploadProofFiles.withdrawal_receipt_proof_files.*.extension.string')
        ];
    }
}
