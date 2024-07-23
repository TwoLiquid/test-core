<?php

namespace App\Http\Requests\Api\Admin\General\TaxRule\Country;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\General\TaxRule\Country
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
            'page'      => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [

            'paginated.boolean' => trans('validations/api/admin/general/taxRule/country/index.paginated.boolean'),
            'page.integer'      => trans('validations/api/admin/general/taxRule/country/index.page.integer')
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