<?php

namespace App\Http\Requests\Api\Guest\Unit;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetByCategoriesRequest
 *
 * @package App\Http\Requests\Api\Guest\Activity
 */
class GetByActivitiesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'activities_ids'   => 'array|nullable',
            'activities_ids.*' => 'required|integer|exists:activities,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'activities_ids.array'      => trans('validations/api/guest/unit/getByActivities.activities_ids.array'),
            'activities_ids.*.required' => trans('validations/api/guest/unit/getByActivities.activities_ids.*.required'),
            'activities_ids.*.integer'  => trans('validations/api/guest/unit/getByActivities.activities_ids.*.integer'),
            'activities_ids.*.exists'   => trans('validations/api/guest/unit/getByActivities.activities_ids.*.exists'),
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
            'activities_ids' => isset($params['activities_ids']) ? explodeUrlIds($params['activities_ids']) : null
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
