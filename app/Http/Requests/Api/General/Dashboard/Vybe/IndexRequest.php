<?php

namespace App\Http\Requests\Api\General\Dashboard\Vybe;

use App\Http\Requests\Api\BaseRequest;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Type\VybeTypeList;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Vybe
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'types_ids'      => 'array|nullable',
            'types_ids.*'    => 'required|integer|between:1,3',
            'statuses_ids'   => 'array|nullable',
            'statuses_ids.*' => 'required|integer|between:1,5',
            'per_page'       => 'integer|nullable',
            'page'           => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'types_ids.array'         => trans('validations/api/general/dashboard/vybe/index.types_ids.array'),
            'types_ids.*.required'    => trans('validations/api/general/dashboard/vybe/index.types_ids.*.required'),
            'types_ids.*.integer'     => trans('validations/api/general/dashboard/vybe/index.types_ids.*.integer'),
            'types_ids.*.between'     => trans('validations/api/general/dashboard/vybe/index.types_ids.*.between'),
            'statuses_ids.array'      => trans('validations/api/general/dashboard/vybe/index.statuses_ids.array'),
            'statuses_ids.*.required' => trans('validations/api/general/dashboard/vybe/index.statuses_ids.*.required'),
            'statuses_ids.*.integer'  => trans('validations/api/general/dashboard/vybe/index.statuses_ids.*.integer'),
            'statuses_ids.*.between'  => trans('validations/api/general/dashboard/vybe/index.statuses_ids.*.between'),
            'per_page.integer'        => trans('validations/api/general/dashboard/vybe/index.per_page.integer'),
            'page.integer'            => trans('validations/api/general/dashboard/vybe/index.page.integer')
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

        $typesIds = isset($params['types_ids']) ?
            explodeUrlIds($params['types_ids']) :
            null;

        if (empty($typesIds)) {
            $typesIds = VybeTypeList::getItems()
                ->pluck('id')
                ->values()
                ->toArray();
        }

        $statusesIds = isset($params['statuses_ids']) ?
            explodeUrlIds($params['statuses_ids']) :
            null;

        if (empty($statusesIds)) {
            $statusesIds = VybeStatusList::getItems()
                ->pluck('id')
                ->values()
                ->toArray();
        }

        $this->merge([
            'types_ids'    => $typesIds,
            'statuses_ids' => $statusesIds,
            'per_page'     => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'         => isset($params['page']) ? (int) $params['page'] : null
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
