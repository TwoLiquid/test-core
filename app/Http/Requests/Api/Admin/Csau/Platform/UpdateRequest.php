<?php

namespace App\Http\Requests\Api\Admin\Csau\Platform;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Platform
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'                  => 'string|nullable',
            'voice_chat'            => 'boolean|nullable',
            'visible_in_voice_chat' => 'boolean|nullable',
            'video_chat'            => 'boolean|nullable',
            'visible_in_video_chat' => 'boolean|nullable',
            'icon'                  => 'array|nullable',
            'icon.content'          => 'string|sometimes',
            'icon.extension'        => 'string|sometimes',
            'icon.mime'             => 'string|sometimes'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.string'                   => trans('validations/api/admin/csau/platform/update.name.string'),
            'voice_chat.boolean'            => trans('validations/api/admin/csau/platform/update.voice_chat.boolean'),
            'visible_in_voice_chat.boolean' => trans('validations/api/admin/csau/platform/update.visible_in_voice_chat.boolean'),
            'video_chat.boolean'            => trans('validations/api/admin/csau/platform/update.video_chat.boolean'),
            'visible_in_video_chat.boolean' => trans('validations/api/admin/csau/platform/update.visible_in_video_chat.boolean'),
            'icon.array'                    => trans('validations/api/admin/csau/platform/update.icon.array'),
            'icon.content.string'           => trans('validations/api/admin/csau/platform/update.icon.content.string'),
            'icon.extension.string'         => trans('validations/api/admin/csau/platform/update.icon.extension.string'),
            'icon.mime.string'              => trans('validations/api/admin/csau/platform/update.icon.mime.string')
        ];
    }
}
