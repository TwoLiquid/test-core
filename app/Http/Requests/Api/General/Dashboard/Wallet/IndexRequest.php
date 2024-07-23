<?php

namespace App\Http\Requests\Api\General\Dashboard\Wallet;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Wallet
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'balance_type_id' => 'required|integer|between:1,3',
            'search'          => 'string|nullable',
            'date_from'       => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'         => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'page'            => 'integer|nullable',
            'per_page'        => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'balance_type_id.required' => trans('validations/api/general/dashboard/wallet/index.balance_type_id.required'),
            'balance_type_id.integer'  => trans('validations/api/general/dashboard/wallet/index.balance_type_id.integer'),
            'balance_type_id.between'  => trans('validations/api/general/dashboard/wallet/index.balance_type_id.between'),
            'search.string'            => trans('validations/api/general/dashboard/wallet/index.search.string'),
            'date_from.string'         => trans('validations/api/general/dashboard/wallet/index.date_from.string'),
            'date_from.date_format'    => trans('validations/api/general/dashboard/wallet/index.date_from.date_format'),
            'date_to.string'           => trans('validations/api/general/dashboard/wallet/index.date_to.string'),
            'date_to.date_format'      => trans('validations/api/general/dashboard/wallet/index.date_to.date_format'),
            'page.integer'             => trans('validations/api/general/dashboard/wallet/index.page.integer'),
            'per_page.integer'         => trans('validations/api/general/dashboard/wallet/index.per_page.integer')
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation() : void
    {
        $params = $this->all();

        $this->merge([
            'balance_type_id' => isset($params['balance_type_id']) ? (int) $params['balance_type_id'] : null,
            'page'            => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'        => isset($params['per_page']) ? (int) $params['per_page'] : null
        ]);
    }

    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null) : array
    {
        return array_merge(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
