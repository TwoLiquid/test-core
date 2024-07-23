<?php

namespace App\Http\Requests\Api\Guest\Profile\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetTimeslotUsersRequest
 *
 * @package App\Http\Requests\Api\Guest\Profile\Vybe
 */
class ShowRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'username' => 'string|nullable',
            'offset'   => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'username.string' => trans('validations/api/guest/profile/vybe/show.username.string'),
            'offset.integer'  => trans('validations/api/guest/profile/vybe/show.offset.integer')
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
            'offset' => isset($params['offset']) ? (int) $params['offset'] : null
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
