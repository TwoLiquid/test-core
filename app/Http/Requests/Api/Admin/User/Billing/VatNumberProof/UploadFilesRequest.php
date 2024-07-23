<?php

namespace App\Http\Requests\Api\Admin\User\Billing\VatNumberProof;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UploadFilesRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Billing\VatNumberProof
 */
class UploadFilesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'vat_number_proof_files'                 => 'array|nullable',
            'vat_number_proof_files.*.content'       => 'string|nullable',
            'vat_number_proof_files.*.original_name' => 'string|nullable',
            'vat_number_proof_files.*.extension'     => 'string|nullable',
            'vat_number_proof_files.*.mime'          => 'string|nullable',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'vat_number_proof_files.array'                  => trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.vat_number_proof_files.array'),
            'vat_number_proof_files.*.content.string'       => trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.vat_number_proof_files.*.content.string'),
            'vat_number_proof_files.*.original_name.string' => trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.vat_number_proof_files.*.original_name.string'),
            'vat_number_proof_files.*.mime.string'          => trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.vat_number_proof_files.*.mime.string'),
            'vat_number_proof_files.*.extension.string'     => trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.vat_number_proof_files.*.extension.string')
        ];
    }
}
