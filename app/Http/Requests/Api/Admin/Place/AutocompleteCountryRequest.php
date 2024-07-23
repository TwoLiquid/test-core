<?php

namespace App\Http\Requests\Api\Admin\Place;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AutocompleteCountryRequest
 *
 * @package App\Http\Requests\Api\Admin\Place
 */
class AutocompleteCountryRequest extends BaseRequest
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
            'search.required' => trans('validations/api/admin/place/autocompleteCountry.search.required'),
            'search.string'   => trans('validations/api/admin/place/autocompleteCountry.search.string')
        ];
    }
}
