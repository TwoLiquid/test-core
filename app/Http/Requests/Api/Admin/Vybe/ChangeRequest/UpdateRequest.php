<?php

namespace App\Http\Requests\Api\Admin\Vybe\ChangeRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\ChangeRequest
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'title_status_id'                                   => 'integer|in:2,3|nullable',
            'category_status_id'                                => 'integer|in:2,3|nullable',
            'subcategory_status_id'                             => 'integer|in:2,3|nullable',
            'devices_status_id'                                 => 'integer|in:2,3|nullable',
            'activity_status_id'                                => 'integer|in:2,3|nullable',
            'period_status_id'                                  => 'integer|in:2,3|nullable',
            'user_count_status_id'                              => 'integer|in:2,3|nullable',
            'appearance_cases'                                  => 'required|array',
            'appearance_cases.voice_chat'                       => 'array|nullable',
            'appearance_cases.voice_chat.price_status_id'       => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.voice_chat.unit_status_id'        => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.voice_chat.description_status_id' => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.voice_chat.platforms_status_id'   => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.video_chat'                       => 'array|nullable',
            'appearance_cases.video_chat.price_status_id'       => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.video_chat.unit_status_id'        => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.video_chat.description_status_id' => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.video_chat.platforms_status_id'   => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.real_life'                        => 'array|nullable',
            'appearance_cases.real_life.price_status_id'        => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.real_life.unit_status_id'         => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.real_life.description_status_id'  => 'integer|in:2,3|nullable|sometimes',
            'appearance_cases.real_life.city_place_status_id'   => 'integer|in:2,3|nullable|sometimes',
            'schedules_status_id'                               => 'integer|in:2,3|nullable',
            'declined_images_ids'                               => 'array|nullable',
            'declined_videos_ids'                               => 'array|nullable',
            'access_status_id'                                  => 'integer|in:2,3|nullable',
            'showcase_status_id'                                => 'integer|in:2,3|nullable',
            'status_status_id'                                  => 'integer|in:2,3|nullable',
            'toast_message_text'                                => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'title_status_id.integer'                                    => trans('validations/api/admin/vybe/changeRequest/update.title_status_id.integer'),
            'title_status_id.in'                                         => trans('validations/api/admin/vybe/changeRequest/update.title_status_id.in'),
            'category_status_id.integer'                                 => trans('validations/api/admin/vybe/changeRequest/update.category_status_id.integer'),
            'category_status_id.in'                                      => trans('validations/api/admin/vybe/changeRequest/update.category_status_id.in'),
            'subcategory_status_id.integer'                              => trans('validations/api/admin/vybe/changeRequest/update.subcategory_status_id.integer'),
            'subcategory_status_id.in'                                   => trans('validations/api/admin/vybe/changeRequest/update.subcategory_status_id.in'),
            'devices_status_id.integer'                                  => trans('validations/api/admin/vybe/changeRequest/update.devices_status_id.integer'),
            'devices_status_id.in'                                       => trans('validations/api/admin/vybe/changeRequest/update.devices_status_id.in'),
            'activity_status_id.integer'                                 => trans('validations/api/admin/vybe/changeRequest/update.activity_status_id.integer'),
            'activity_status_id.in'                                      => trans('validations/api/admin/vybe/changeRequest/update.activity_status_id.in'),
            'period_status_id.integer'                                   => trans('validations/api/admin/vybe/changeRequest/update.period_status_id.integer'),
            'period_status_id.in'                                        => trans('validations/api/admin/vybe/changeRequest/update.period_status_id.in'),
            'user_count_status_id.integer'                               => trans('validations/api/admin/vybe/changeRequest/update.user_count_status_id.integer'),
            'user_count_status_id.in'                                    => trans('validations/api/admin/vybe/changeRequest/update.user_count_status_id.in'),
            'appearance_cases.required'                                  => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.required'),
            'appearance_cases.array'                                     => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.array'),
            'appearance_cases.voice_chat.array'                          => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.array'),
            'appearance_cases.voice_chat.price_status_id.integer'        => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.price_status_id.integer'),
            'appearance_cases.voice_chat.price_status_id.in'             => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.price_status_id.in'),
            'appearance_cases.voice_chat.unit_status_id.integer'         => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.unit_status_id.integer'),
            'appearance_cases.voice_chat.unit_status_id.in'              => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.unit_status_id.in'),
            'appearance_cases.voice_chat.description_status_id.integer'  => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.description_status_id.integer'),
            'appearance_cases.voice_chat.description_status_id.in'       => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.description_status_id.in'),
            'appearance_cases.voice_chat.platforms_status_id.integer'    => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.platforms_status_id.integer'),
            'appearance_cases.voice_chat.platforms_status_id.in'         => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.voice_chat.platforms_status_id.in'),
            'appearance_cases.video_chat.array'                          => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.array'),
            'appearance_cases.video_chat.price_status_id.integer'        => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.price_status_id.integer'),
            'appearance_cases.video_chat.price_status_id.in'             => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.price_status_id.in'),
            'appearance_cases.video_chat.unit_status_id.integer'         => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.unit_status_id.integer'),
            'appearance_cases.video_chat.unit_status_id.in'              => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.unit_status_id.in'),
            'appearance_cases.video_chat.description_status_id.integer'  => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.description_status_id.integer'),
            'appearance_cases.video_chat.description_status_id.in'       => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.description_status_id.in'),
            'appearance_cases.video_chat.platforms_status_id.integer'    => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.platforms_status_id.integer'),
            'appearance_cases.video_chat.platforms_status_id.in'         => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.video_chat.platforms_status_id.in'),
            'appearance_cases.real_life.array'                           => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.array'),
            'appearance_cases.real_life.price_status_id.integer'         => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.price_status_id.integer'),
            'appearance_cases.real_life.price_status_id.in'              => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.price_status_id.in'),
            'appearance_cases.real_life.unit_status_id.integer'          => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.unit_status_id.integer'),
            'appearance_cases.real_life.unit_status_id.in'               => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.unit_status_id.in'),
            'appearance_cases.real_life.description_status_id.integer'   => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.description_status_id.integer'),
            'appearance_cases.real_life.description_status_id.in'        => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.description_status_id.in'),
            'appearance_cases.real_life.city_place_status_id.integer'    => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.city_place_status_id.integer'),
            'appearance_cases.real_life.city_place_status_id.in'         => trans('validations/api/admin/vybe/changeRequest/update.appearance_cases.real_life.city_place_status_id.in'),
            'schedules_status_id.integer'                                => trans('validations/api/admin/vybe/changeRequest/update.schedules_status_id.integer'),
            'schedules_status_id.in'                                     => trans('validations/api/admin/vybe/changeRequest/update.schedules_status_id.in'),
            'declined_images_ids.array'                                  => trans('validations/api/admin/vybe/changeRequest/update.declined_images_ids.array'),
            'declined_videos_ids.array'                                  => trans('validations/api/admin/vybe/changeRequest/update.declined_videos_ids.array'),
            'access_status_id.integer'                                   => trans('validations/api/admin/vybe/changeRequest/update.access_status_id.integer'),
            'access_status_id.in'                                        => trans('validations/api/admin/vybe/changeRequest/update.access_status_id.in'),
            'showcase_status_id.integer'                                 => trans('validations/api/admin/vybe/changeRequest/update.showcase_status_id.integer'),
            'showcase_status_id.in'                                      => trans('validations/api/admin/vybe/changeRequest/update.showcase_status_id.in'),
            'status_status_id.integer'                                   => trans('validations/api/admin/vybe/changeRequest/update.status_status_id.integer'),
            'status_status_id.in'                                        => trans('validations/api/admin/vybe/changeRequest/update.status_status_id.in'),
            'toast_message_text.string'                                  => trans('validations/api/admin/vybe/changeRequest/update.toast_message_text.string')
        ];
    }
}
