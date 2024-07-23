<?php

namespace App\Http\Requests\Api\Admin\Csau\Platform;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Platform
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'                  => 'required|string',
            'voice_chat'            => 'required|boolean',
            'visible_in_voice_chat' => 'required|boolean',
            'video_chat'            => 'required|boolean',
            'visible_in_video_chat' => 'required|boolean',
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
            'name.required'                  => trans('validations/api/admin/csau/platform/store.name.required'),
            'name.string'                    => trans('validations/api/admin/csau/platform/store.name.string'),
            'voice_chat.required'            => trans('validations/api/admin/csau/platform/store.voice_chat.required'),
            'voice_chat.boolean'             => trans('validations/api/admin/csau/platform/store.voice_chat.boolean'),
            'visible_in_voice_chat.required' => trans('validations/api/admin/csau/platform/store.visible_in_voice_chat.required'),
            'visible_in_voice_chat.boolean'  => trans('validations/api/admin/csau/platform/store.visible_in_voice_chat.boolean'),
            'video_chat.required'            => trans('validations/api/admin/csau/platform/store.video_chat.required'),
            'video_chat.boolean'             => trans('validations/api/admin/csau/platform/store.video_chat.boolean'),
            'visible_in_video_chat.required' => trans('validations/api/admin/csau/platform/store.visible_in_video_chat.required'),
            'visible_in_video_chat.boolean'  => trans('validations/api/admin/csau/platform/store.visible_in_video_chat.boolean'),
            'icon.array'                     => trans('validations/api/admin/csau/platform/store.icon.array'),
            'icon.content.string'            => trans('validations/api/admin/csau/platform/store.icon.content.string'),
            'icon.extension.string'          => trans('validations/api/admin/csau/platform/store.icon.extension.string'),
            'icon.mime.string'               => trans('validations/api/admin/csau/platform/store.icon.mime.string')
        ];
    }
}
