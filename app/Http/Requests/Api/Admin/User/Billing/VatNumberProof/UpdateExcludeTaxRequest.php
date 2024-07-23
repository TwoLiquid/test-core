<?php

namespace App\Http\Requests\Api\Admin\User\Billing\VatNumberProof;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateExcludeTaxRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Billing\VatNumberProof
 */
class UpdateExcludeTaxRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'exclude_tax' => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'exclude_tax.required' => trans('validations/api/admin/user/billing/vatNumberProof/updateExcludeTax.exclude_tax.required'),
            'exclude_tax.boolean'  => trans('validations/api/admin/user/billing/vatNumberProof/updateExcludeTax.exclude_tax.boolean')
        ];
    }
}
