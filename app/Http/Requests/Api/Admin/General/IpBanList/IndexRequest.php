<?php

namespace App\Http\Requests\Api\Admin\General\IpBanList;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\General\IpBanList
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
            'page'      => 'integer|nullable',
            'per_page'  => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'paginated.boolean' => trans('validations/api/admin/general/ipBanList/index.paginated.boolean'),
            'page.integer'      => trans('validations/api/admin/general/ipBanList/index.page.integer'),
            'per_page.integer'  => trans('validations/api/admin/general/ipBanList/index.per_page.integer')
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
            'page'      => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'  => isset($params['per_page']) ? (int) $params['per_page'] : null
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
