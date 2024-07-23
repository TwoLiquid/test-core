<?php

namespace App\Http\Requests\Api\Admin\User\Note;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Note
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'text' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'text.required' => trans('validations/api/admin/user/note/store.text.required'),
            'text.string'   => trans('validations/api/admin/user/note/store.text.string')
        ];
    }
}
