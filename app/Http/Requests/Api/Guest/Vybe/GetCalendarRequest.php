<?php

namespace App\Http\Requests\Api\Guest\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetCalendarRequest
 *
 * @package App\Http\Requests\Api\Guest\Vybe
 */
class GetCalendarRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'offset' => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'offset.integer' => trans('validations/api/guest/vybe/getCalendar.offset.integer')
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
