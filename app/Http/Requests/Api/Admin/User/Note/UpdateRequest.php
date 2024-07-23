<?php

namespace App\Http\Requests\Api\Admin\User\Note;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Note
 */
class UpdateRequest extends BaseRequest
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
            'text.required' => trans('validations/api/admin/user/note/update.text.required'),
            'text.string'   => trans('validations/api/admin/user/note/update.text.string')
        ];
    }
}
