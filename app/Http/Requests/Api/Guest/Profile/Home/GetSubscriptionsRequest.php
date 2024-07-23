<?php

namespace App\Http\Requests\Api\Guest\Profile\Home;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetSubscriptionsRequest
 *
 * @package App\Http\Requests\Api\Guest\Profile\Home
 */
class GetSubscriptionsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'page'   => 'integer|nullable',
            'search' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'page.integer'  => trans('validations/api/guest/profile/home/getSubscriptions/index.page.integer'),
            'search.string' => trans('validations/api/guest/profile/home/getSubscriptions/index.search.string')
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
            'page' => isset($params['page']) ? (int) $params['page'] : null
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
