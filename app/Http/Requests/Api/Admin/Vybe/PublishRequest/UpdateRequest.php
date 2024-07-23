<?php

namespace App\Http\Requests\Api\Admin\Vybe\PublishRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\PublishRequest
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'title_status_id'                                   => 'required|integer|in:2,3',
            'category_status_id'                                => 'required|integer|in:2,3',
            'subcategory_status_id'                             => 'integer|in:2,3|nullable',
            'devices_status_id'                                 => 'integer|in:2,3|nullable',
            'activity_status_id'                                => 'required|integer|in:2,3',
            'period_status_id'                                  => 'required|integer|in:2,3',
            'user_count_status_id'                              => 'required|integer|in:2,3',
            'appearance_cases'                                  => 'required|array',
            'appearance_cases.voice_chat'                       => 'array|nullable',
            'appearance_cases.voice_chat.price_status_id'       => 'required|integer|in:2,3|sometimes',
            'appearance_cases.voice_chat.unit_status_id'        => 'required|integer|in:2,3|sometimes',
            'appearance_cases.voice_chat.description_status_id' => 'required|integer|in:2,3|sometimes',
            'appearance_cases.voice_chat.platforms_status_id'   => 'required|integer|in:2,3|sometimes',
            'appearance_cases.video_chat'                       => 'array|nullable',
            'appearance_cases.video_chat.price_status_id'       => 'required|integer|in:2,3|sometimes',
            'appearance_cases.video_chat.unit_status_id'        => 'required|integer|in:2,3|sometimes',
            'appearance_cases.video_chat.description_status_id' => 'required|integer|in:2,3|sometimes',
            'appearance_cases.video_chat.platforms_status_id'   => 'required|integer|in:2,3|sometimes',
            'appearance_cases.real_life'                        => 'array|nullable',
            'appearance_cases.real_life.price_status_id'        => 'required|integer|in:2,3|sometimes',
            'appearance_cases.real_life.unit_status_id'         => 'required|integer|in:2,3|sometimes',
            'appearance_cases.real_life.description_status_id'  => 'required|integer|in:2,3|sometimes',
            'appearance_cases.real_life.city_place_status_id'   => 'required|integer|in:2,3|sometimes',
            'schedules_status_id'                               => 'required|integer|in:2,3',
            'declined_images_ids'                               => 'array|nullable',
            'declined_videos_ids'                               => 'array|nullable',
            'access_status_id'                                  => 'required|integer|in:2,3',
            'showcase_status_id'                                => 'required|integer|in:2,3',
            'order_accept_status_id'                            => 'required|integer|in:2,3',
            'age_limit_id'                                      => 'required|integer|in:1,2,3',
            'status_status_id'                                  => 'required|integer|in:2,3',
            'toast_message_text'                                => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'title_status_id.required'                                   => trans('validations/api/admin/vybe/publishRequest/update.title_status_id.required'),
            'title_status_id.integer'                                    => trans('validations/api/admin/vybe/publishRequest/update.title_status_id.integer'),
            'title_status_id.in'                                         => trans('validations/api/admin/vybe/publishRequest/update.title_status_id.in'),
            'category_status_id.required'                                => trans('validations/api/admin/vybe/publishRequest/update.category_status_id.required'),
            'category_status_id.integer'                                 => trans('validations/api/admin/vybe/publishRequest/update.category_status_id.integer'),
            'category_status_id.in'                                      => trans('validations/api/admin/vybe/publishRequest/update.category_status_id.in'),
            'subcategory_status_id.integer'                              => trans('validations/api/admin/vybe/publishRequest/update.subcategory_status_id.integer'),
            'subcategory_status_id.in'                                   => trans('validations/api/admin/vybe/publishRequest/update.subcategory_status_id.in'),
            'devices_status_id.integer'                                  => trans('validations/api/admin/vybe/publishRequest/update.devices_status_id.integer'),
            'devices_status_id.in'                                       => trans('validations/api/admin/vybe/publishRequest/update.devices_status_id.in'),
            'activity_status_id.required'                                => trans('validations/api/admin/vybe/publishRequest/update.activity_status_id.required'),
            'activity_status_id.integer'                                 => trans('validations/api/admin/vybe/publishRequest/update.activity_status_id.integer'),
            'activity_status_id.in'                                      => trans('validations/api/admin/vybe/publishRequest/update.activity_status_id.in'),
            'period_status_id.required'                                  => trans('validations/api/admin/vybe/publishRequest/update.period_status_id.required'),
            'period_status_id.integer'                                   => trans('validations/api/admin/vybe/publishRequest/update.period_status_id.integer'),
            'period_status_id.in'                                        => trans('validations/api/admin/vybe/publishRequest/update.period_status_id.in'),
            'user_count_status_id.required'                              => trans('validations/api/admin/vybe/publishRequest/update.user_count_status_id.required'),
            'user_count_status_id.integer'                               => trans('validations/api/admin/vybe/publishRequest/update.user_count_status_id.integer'),
            'user_count_status_id.in'                                    => trans('validations/api/admin/vybe/publishRequest/update.user_count_status_id.in'),
            'appearance_cases.required'                                  => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.required'),
            'appearance_cases.array'                                     => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.array'),
            'appearance_cases.voice_chat.array'                          => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.array'),
            'appearance_cases.voice_chat.price_status_id.required'       => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.price_status_id.required'),
            'appearance_cases.voice_chat.price_status_id.integer'        => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.price_status_id.integer'),
            'appearance_cases.voice_chat.price_status_id.in'             => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.price_status_id.in'),
            'appearance_cases.voice_chat.unit_status_id.required'        => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.unit_status_id.required'),
            'appearance_cases.voice_chat.unit_status_id.integer'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.unit_status_id.integer'),
            'appearance_cases.voice_chat.unit_status_id.in'              => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.unit_status_id.in'),
            'appearance_cases.voice_chat.description_status_id.required' => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.description_status_id.required'),
            'appearance_cases.voice_chat.description_status_id.integer'  => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.description_status_id.integer'),
            'appearance_cases.voice_chat.description_status_id.in'       => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.description_status_id.in'),
            'appearance_cases.voice_chat.platforms_status_id.required'   => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.platforms_status_id.required'),
            'appearance_cases.voice_chat.platforms_status_id.integer'    => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.platforms_status_id.integer'),
            'appearance_cases.voice_chat.platforms_status_id.in'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.voice_chat.platforms_status_id.in'),
            'appearance_cases.video_chat.array'                          => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.array'),
            'appearance_cases.video_chat.price_status_id.required'       => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.price_status_id.required'),
            'appearance_cases.video_chat.price_status_id.integer'        => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.price_status_id.integer'),
            'appearance_cases.video_chat.price_status_id.in'             => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.price_status_id.in'),
            'appearance_cases.video_chat.unit_status_id.required'        => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.unit_status_id.required'),
            'appearance_cases.video_chat.unit_status_id.integer'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.unit_status_id.integer'),
            'appearance_cases.video_chat.unit_status_id.in'              => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.unit_status_id.in'),
            'appearance_cases.video_chat.description_status_id.required' => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.description_status_id.required'),
            'appearance_cases.video_chat.description_status_id.integer'  => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.description_status_id.integer'),
            'appearance_cases.video_chat.description_status_id.in'       => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.description_status_id.in'),
            'appearance_cases.video_chat.platforms_status_id.required'   => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.platforms_status_id.required'),
            'appearance_cases.video_chat.platforms_status_id.integer'    => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.platforms_status_id.integer'),
            'appearance_cases.video_chat.platforms_status_id.in'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.video_chat.platforms_status_id.in'),
            'appearance_cases.real_life.array'                           => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.array'),
            'appearance_cases.real_life.price_status_id.required'        => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.price_status_id.required'),
            'appearance_cases.real_life.price_status_id.integer'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.price_status_id.integer'),
            'appearance_cases.real_life.price_status_id.in'              => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.price_status_id.in'),
            'appearance_cases.real_life.unit_status_id.required'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.unit_status_id.required'),
            'appearance_cases.real_life.unit_status_id.integer'          => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.unit_status_id.integer'),
            'appearance_cases.real_life.unit_status_id.in'               => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.unit_status_id.in'),
            'appearance_cases.real_life.description_status_id.required'  => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.description_status_id.required'),
            'appearance_cases.real_life.description_status_id.integer'   => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.description_status_id.integer'),
            'appearance_cases.real_life.description_status_id.in'        => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.description_status_id.in'),
            'appearance_cases.real_life.city_place_status_id.required'   => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.city_place_status_id.required'),
            'appearance_cases.real_life.city_place_status_id.integer'    => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.city_place_status_id.integer'),
            'appearance_cases.real_life.city_place_status_id.in'         => trans('validations/api/admin/vybe/publishRequest/update.appearance_cases.real_life.city_place_status_id.in'),
            'schedules_status_id.required'                               => trans('validations/api/admin/vybe/publishRequest/update.schedules_status_id.required'),
            'schedules_status_id.integer'                                => trans('validations/api/admin/vybe/publishRequest/update.schedules_status_id.integer'),
            'schedules_status_id.in'                                     => trans('validations/api/admin/vybe/publishRequest/update.schedules_status_id.in'),
            'declined_images_ids.array'                                  => trans('validations/api/admin/vybe/publishRequest/update.declined_images_ids.array'),
            'declined_videos_ids.array'                                  => trans('validations/api/admin/vybe/publishRequest/update.declined_videos_ids.array'),
            'access_status_id.required'                                  => trans('validations/api/admin/vybe/publishRequest/update.access_status_id.required'),
            'access_status_id.integer'                                   => trans('validations/api/admin/vybe/publishRequest/update.access_status_id.integer'),
            'access_status_id.in'                                        => trans('validations/api/admin/vybe/publishRequest/update.access_status_id.in'),
            'showcase_status_id.required'                                => trans('validations/api/admin/vybe/publishRequest/update.showcase_status_id.required'),
            'showcase_status_id.integer'                                 => trans('validations/api/admin/vybe/publishRequest/update.showcase_status_id.integer'),
            'showcase_status_id.in'                                      => trans('validations/api/admin/vybe/publishRequest/update.showcase_status_id.in'),
            'order_accept_status_id.required'                            => trans('validations/api/admin/vybe/publishRequest/update.order_accept_status_id.required'),
            'order_accept_status_id.integer'                             => trans('validations/api/admin/vybe/publishRequest/update.order_accept_status_id.integer'),
            'order_accept_status_id.in'                                  => trans('validations/api/admin/vybe/publishRequest/update.order_accept_status_id.in'),
            'age_limit_id.required'                                      => trans('validations/api/admin/vybe/publishRequest/update.age_limit_id.required'),
            'age_limit_id.integer'                                       => trans('validations/api/admin/vybe/publishRequest/update.age_limit_id.integer'),
            'age_limit_id.in'                                            => trans('validations/api/admin/vybe/publishRequest/update.age_limit_id.in'),
            'status_status_id.required'                                  => trans('validations/api/admin/vybe/publishRequest/update.status_status_id.required'),
            'status_status_id.integer'                                   => trans('validations/api/admin/vybe/publishRequest/update.status_status_id.integer'),
            'status_status_id.in'                                        => trans('validations/api/admin/vybe/publishRequest/update.status_status_id.in'),
            'toast_message_text.string'                                  => trans('validations/api/admin/vybe/publishRequest/update.toast_message_text.string')
        ];
    }
}
