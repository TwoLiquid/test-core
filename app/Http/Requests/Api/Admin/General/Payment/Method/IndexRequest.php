<?php

namespace App\Http\Requests\Api\Admin\General\Payment\Method;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Payment\Method
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'paginated' => 'boolean|nullable',
            'per_page'  => 'integer|nullable',
            'page'      => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'paginated.boolean' => trans('validations/api/admin/general/payment/method/index.paginated.boolean'),
            'per_page.integer'  => trans('validations/api/admin/general/payment/method/index.per_page.integer'),
            'page.integer'      => trans('validations/api/admin/general/payment/method/index.page.integer')
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
            'paginated' => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'  => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'      => isset($params['page']) ? (int) $params['page'] : null
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
