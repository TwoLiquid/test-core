<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetBlockedUsersRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class GetBlockedUsersRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'search' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'search.string' => trans('validations/api/general/setting/account/getBlockedUsers.search.string'),
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
            'paginated' => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'page'      => isset($params['page']) ? (int) $params['page'] : null
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
