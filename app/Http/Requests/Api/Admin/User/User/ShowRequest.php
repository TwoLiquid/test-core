<?php

namespace App\Http\Requests\Api\Admin\User\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ShowRequest
 *
 * @package App\Http\Requests\Api\Admin\User\User
 */
class ShowRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'form'     => 'boolean|nullable',
            'requests' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'form.boolean'     => trans('validations/api/general/user/user/show.form.boolean'),
            'requests.boolean' => trans('validations/api/general/user/user/show.requests.boolean')
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
            'form'     => isset($params['form']) ? filter_var($params['form'], FILTER_VALIDATE_BOOLEAN) : null,
            'requests' => isset($params['requests']) ? filter_var($params['requests'], FILTER_VALIDATE_BOOLEAN) : null
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
