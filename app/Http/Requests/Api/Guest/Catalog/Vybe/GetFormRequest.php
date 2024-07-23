<?php

namespace App\Http\Requests\Api\Guest\Catalog\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetFormRequest
 *
 * @package App\Http\Requests\Api\Guest\Catalog\Vybe
 */
class GetFormRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'category_id' => 'integer|exists:categories,id|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'category_id.integer' => trans('validations/api/guest/catalog/vybe/getForm.category_id.integer'),
            'category_id.exists'  => trans('validations/api/guest/catalog/vybe/getForm.category_id.exists')
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
            'category_id' => isset($params['category_id']) ? (int) $params['category_id'] : null
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
