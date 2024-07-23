<?php

namespace App\Http\Requests\Api\General\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ShowRequest
 *
 * @package App\Http\Requests\Api\General\Vybe
 */
class ShowRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'with_form' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'with_form.boolean' => trans('validations/api/general/vybe/show.paginated.boolean')
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
            'with_form' => isset($params['with_form']) ? (bool) $params['with_form'] : null
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
