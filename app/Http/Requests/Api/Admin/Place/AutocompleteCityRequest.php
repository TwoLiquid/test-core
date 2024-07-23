<?php

namespace App\Http\Requests\Api\Admin\Place;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AutocompleteCityRequest
 *
 * @package App\Http\Requests\Api\Admin\Place
 */
class AutocompleteCityRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'search' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'search.required' => trans('validations/api/admin/place/autocompleteCity.search.required'),
            'search.string'   => trans('validations/api/admin/place/autocompleteCity.search.string')
        ];
    }
}
