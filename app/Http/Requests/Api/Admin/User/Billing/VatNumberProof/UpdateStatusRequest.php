<?php

namespace App\Http\Requests\Api\Admin\User\Billing\VatNumberProof;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateStatusRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Billing\VatNumberProof
 */
class UpdateStatusRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'vat_number_proof_status_id' => 'required|integer|in:1,2'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'vat_number_proof_status_id.required' => trans('validations/api/admin/user/billing/vatNumberProof/updateStatus.vat_number_proof_status_id.required'),
            'vat_number_proof_status_id.integer'  => trans('validations/api/admin/user/billing/vatNumberProof/updateStatus.vat_number_proof_status_id.integer'),
            'vat_number_proof_status_id.in'       => trans('validations/api/admin/user/billing/vatNumberProof/updateStatus.vat_number_proof_status_id.in')
        ];
    }
}
