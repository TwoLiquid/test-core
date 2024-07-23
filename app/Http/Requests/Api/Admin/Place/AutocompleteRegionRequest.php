<?php

namespace App\Http\Requests\Api\Admin\Place;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AutocompleteRegionRequest
 *
 * @package App\Http\Requests\Api\Admin\Place
 */
class AutocompleteRegionRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'country_code' => 'required|string|exists:country_places,code',
            'search'       => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'country_code.required' => trans('validations/api/admin/place/autocompleteRegion.country_code.required'),
            'country_code.string'   => trans('validations/api/admin/place/autocompleteRegion.country_code.string'),
            'country_code.exists'   => trans('validations/api/admin/place/autocompleteRegion.country_code.exists'),
            'search.required'       => trans('validations/api/admin/place/autocompleteRegion.search.required'),
            'search.string'         => trans('validations/api/admin/place/autocompleteRegion.search.string')
        ];
    }
}
