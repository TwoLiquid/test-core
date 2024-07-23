<?php

namespace App\Http\Requests\Api\General\Vybe\ChangeRequest;

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
            // 1-st step
            'title'                                        => 'required|string',
            'category_id'                                  => 'integer|exists:categories,id|nullable',
            'category_suggestion'                          => 'string|nullable',
            'subcategory_id'                               => 'integer|exists:categories,id|nullable',
            'subcategory_suggestion'                       => 'string|nullable',
            'devices_ids'                                  => 'array|nullable',
            'devices_ids.*'                                => 'required|integer|exists:devices,id',
            'device_suggestion'                            => 'string|nullable',
            'activity_id'                                  => 'integer|exists:activities,id|nullable',
            'activity_suggestion'                          => 'string|nullable',
            'period_id'                                    => 'required|integer|between:1,2',
            'user_count'                                   => 'required|integer',
            // 2-nd step
            'appearance_cases'                             => 'required|array',
            'appearance_cases.voice_chat'                  => 'array|nullable',
            'appearance_cases.voice_chat.price'            => 'required|numeric|sometimes',
            'appearance_cases.voice_chat.description'      => 'string|nullable',
            'appearance_cases.voice_chat.unit_id'          => 'integer|exists:units,id|nullable',
            'appearance_cases.voice_chat.unit_suggestion'  => 'string|nullable',
            'appearance_cases.voice_chat.platforms_ids'    => 'array|nullable',
            'appearance_cases.voice_chat.platforms_ids.*'  => 'required|integer|exists:platforms,id|sometimes',
            'appearance_cases.voice_chat.enabled'          => 'required|boolean|sometimes',
            'appearance_cases.video_chat'                  => 'array|nullable',
            'appearance_cases.video_chat.price'            => 'required|numeric|sometimes',
            'appearance_cases.video_chat.description'      => 'string|nullable',
            'appearance_cases.video_chat.unit_id'          => 'integer|exists:units,id|nullable',
            'appearance_cases.video_chat.unit_suggestion'  => 'string|nullable',
            'appearance_cases.video_chat.platforms_ids'    => 'array|nullable',
            'appearance_cases.video_chat.platforms_ids.*'  => 'required|integer|exists:platforms,id|sometimes',
            'appearance_cases.video_chat.enabled'          => 'required|boolean|sometimes',
            'appearance_cases.real_life'                   => 'array|nullable',
            'appearance_cases.real_life.price'             => 'required|numeric|sometimes',
            'appearance_cases.real_life.description'       => 'string|nullable',
            'appearance_cases.real_life.unit_id'           => 'integer|exists:units,id|nullable',
            'appearance_cases.real_life.unit_suggestion'   => 'string|nullable',
            'appearance_cases.real_life.same_location'     => 'required|boolean|sometimes',
            'appearance_cases.real_life.country_id'        => 'integer|exists:countries,id|nullable',
            'appearance_cases.real_life.region_id'         => 'integer|exists:regions,id|nullable',
            'appearance_cases.real_life.region_suggestion' => 'string|nullable',
            'appearance_cases.real_life.city_id'           => 'integer|exists:cities,id|nullable',
            'appearance_cases.real_life.city_suggestion'   => 'string|nullable',
            'appearance_cases.real_life.enabled'           => 'required|boolean|sometimes',
            // 3-rd step
            'schedules'                                    => 'required|array',
            'schedules.*.datetime_from'                    => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'schedules.*.datetime_to'                      => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'order_advance'                                => 'integer|nullable',
            // 4-th step
            'deleted_images_ids'                           => 'array|nullable',
            'deleted_images_ids.*'                         => 'required|integer',
            'deleted_videos_ids'                           => 'array|nullable',
            'deleted_videos_ids.*'                         => 'required|integer',
            'files'                                        => 'array|nullable',
            'files.*.content'                              => 'required|string',
            'files.*.extension'                            => 'required|string',
            'files.*.mime'                                 => 'required|string',
            'files.*.original_name'                        => 'required|string',
            'files.*.main'                                 => 'boolean|nullable',
            // 5-th step
            'access_id'                                    => 'required|integer|between:1,2',
            'access_password'                              => 'string|nullable',
            'showcase_id'                                  => 'required|integer|between:1,2',
            'age_limit_id'                                 => 'integer|between:1,2|nullable',
            'status_id'                                    => 'required|integer|in:2,3'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 1-st step
            'title.required'                                       => trans('validations/api/general/vybe/changeRequest/update.title.required'),
            'title.string'                                         => trans('validations/api/general/vybe/changeRequest/update.title.string'),
            'category_id.integer'                                  => trans('validations/api/general/vybe/changeRequest/update.category_id.integer'),
            'category_id.exists'                                   => trans('validations/api/general/vybe/changeRequest/update.category_id.exists'),
            'category_suggestion.string'                           => trans('validations/api/general/vybe/changeRequest/update.category_suggestion.string'),
            'subcategory_id.integer'                               => trans('validations/api/general/vybe/changeRequest/update.subcategory_id.integer'),
            'subcategory_id.exists'                                => trans('validations/api/general/vybe/changeRequest/update.subcategory_id.exists'),
            'subcategory_suggestion.string'                        => trans('validations/api/general/vybe/changeRequest/update.subcategory_suggestion.string'),
            'devices_ids.array'                                    => trans('validations/api/general/vybe/changeRequest/update.devices_ids.array'),
            'devices_ids.*.required'                               => trans('validations/api/general/vybe/changeRequest/update.devices_ids.*.required'),
            'devices_ids.*.integer'                                => trans('validations/api/general/vybe/changeRequest/update.devices_ids.*.integer'),
            'devices_ids.*.exists'                                 => trans('validations/api/general/vybe/changeRequest/update.devices_ids.*.exists'),
            'device_suggestion.string'                             => trans('validations/api/general/vybe/changeRequest/update.device_suggestion.string'),
            'activity_id.*.integer'                                => trans('validations/api/general/vybe/changeRequest/update.activity_id.integer'),
            'activity_id.*.exists'                                 => trans('validations/api/general/vybe/changeRequest/update.activity_id.exists'),
            'activity_suggestion.string'                           => trans('validations/api/general/vybe/changeRequest/update.activity_suggestion.string'),
            'period_id.required'                                   => trans('validations/api/general/vybe/changeRequest/update.period_id.required'),
            'period_id.integer'                                    => trans('validations/api/general/vybe/changeRequest/update.period_id.integer'),
            'user_count.required'                                  => trans('validations/api/general/vybe/changeRequest/update.user_count.required'),
            'user_count.integer'                                   => trans('validations/api/general/vybe/changeRequest/update.user_count.integer'),
            // 2-nd step
            'appearance_cases.required'                            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.required'),
            'appearance_cases.array'                               => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.array'),
            'appearance_cases.voice_chat.array'                    => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.array'),
            'appearance_cases.voice_chat.price.required'           => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.price.required'),
            'appearance_cases.voice_chat.price.numeric'            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.price.numeric'),
            'appearance_cases.voice_chat.description.string'       => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.description.string'),
            'appearance_cases.voice_chat.unit_id.integer'          => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.unit_id.integer'),
            'appearance_cases.voice_chat.unit_id.exists'           => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.unit_id.exists'),
            'appearance_cases.voice_chat.unit_suggestion.string'   => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.unit_suggestion.string'),
            'appearance_cases.voice_chat.platforms_ids.array'      => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.platforms_ids.array'),
            'appearance_cases.voice_chat.platforms_ids.*.required' => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.platforms_ids.*.required'),
            'appearance_cases.voice_chat.platforms_ids.*.integer'  => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.platforms_ids.*.integer'),
            'appearance_cases.voice_chat.platforms_ids.*.exists'   => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.platforms_ids.*.exists'),
            'appearance_cases.voice_chat.enabled.required'         => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.enabled.required'),
            'appearance_cases.voice_chat.enabled.boolean'          => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.voice_chat.enabled.boolean'),
            'appearance_cases.video_chat.array'                    => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.array'),
            'appearance_cases.video_chat.price.required'           => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.price.required'),
            'appearance_cases.video_chat.price.numeric'            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.price.numeric'),
            'appearance_cases.video_chat.description.string'       => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.description.string'),
            'appearance_cases.video_chat.unit_id.integer'          => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.unit_id.integer'),
            'appearance_cases.video_chat.unit_id.exists'           => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.unit_id.exists'),
            'appearance_cases.video_chat.unit_suggestion.string'   => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.unit_suggestion.string'),
            'appearance_cases.video_chat.platforms_ids.array'      => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.platforms_ids.array'),
            'appearance_cases.video_chat.platforms_ids.*.required' => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.platforms_ids.*.required'),
            'appearance_cases.video_chat.platforms_ids.*.integer'  => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.platforms_ids.*.integer'),
            'appearance_cases.video_chat.platforms_ids.*.exists'   => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.platforms_ids.*.exists'),
            'appearance_cases.video_chat.enabled.required'         => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.enabled.required'),
            'appearance_cases.video_chat.enabled.boolean'          => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.video_chat.enabled.boolean'),
            'appearance_cases.real_life.array'                     => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.array'),
            'appearance_cases.real_life.price.required'            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.price.required'),
            'appearance_cases.real_life.price.numeric'             => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.price.numeric'),
            'appearance_cases.real_life.description.string'        => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.description.string'),
            'appearance_cases.real_life.unit_id.string'            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.unit_id.string'),
            'appearance_cases.real_life.unit_id.exists'            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.unit_id.exists'),
            'appearance_cases.real_life.unit_suggestion.string'    => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.unit_suggestion.string'),
            'appearance_cases.real_life.same_location.required'    => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.same_location.required'),
            'appearance_cases.real_life.same_location.boolean'     => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.same_location.boolean'),
            'appearance_cases.real_life.country_id.integer'        => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.country_id.integer'),
            'appearance_cases.real_life.country_id.exists'         => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.country_id.exists'),
            'appearance_cases.real_life.region_id.integer'         => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.region_id.integer'),
            'appearance_cases.real_life.region_id.exists'          => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.region_id.exists'),
            'appearance_cases.real_life.region_suggestion.string'  => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.region_suggestion.string'),
            'appearance_cases.real_life.city_id.integer'           => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.city_id.integer'),
            'appearance_cases.real_life.city_id.exists'            => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.city_id.exists'),
            'appearance_cases.real_life.city_suggestion.string'    => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.city_suggestion.string'),
            'appearance_cases.real_life.enabled.required'          => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.enabled.required'),
            'appearance_cases.real_life.enabled.boolean'           => trans('validations/api/general/vybe/changeRequest/update.appearance_cases.real_life.enabled.boolean'),
            // 3-rd step
            'schedules.required'                                   => trans('validations/api/general/vybe/changeRequest/update.schedules.required'),
            'schedules.array'                                      => trans('validations/api/general/vybe/changeRequest/update.schedules.array'),
            'schedules.*.datetime_from.required'                   => trans('validations/api/general/vybe/changeRequest/update.schedules.*.datetime_from.required'),
            'schedules.*.datetime_from.date_format'                => trans('validations/api/general/vybe/changeRequest/update.schedules.*.datetime_from.date_format'),
            'schedules.*.datetime_to.required'                     => trans('validations/api/general/vybe/changeRequest/update.schedules.*.datetime_to.required'),
            'schedules.*.datetime_to.date_format'                  => trans('validations/api/general/vybe/changeRequest/update.schedules.*.datetime_to.date_format'),
            'order_advance.integer'                                => trans('validations/api/general/vybe/changeRequest/update.order_advance.integer'),
            // 4-th step
            'deleted_images_ids.array'                             => trans('validations/api/general/vybe/changeRequest/update.deleted_images_ids.array'),
            'deleted_images_ids.*.required'                        => trans('validations/api/general/vybe/changeRequest/update.deleted_images_ids.*.required'),
            'deleted_images_ids.*.integer'                         => trans('validations/api/general/vybe/changeRequest/update.deleted_images_ids.*.integer'),
            'deleted_videos_ids.array'                             => trans('validations/api/general/vybe/changeRequest/update.deleted_videos_ids.array'),
            'deleted_videos_ids.*.required'                        => trans('validations/api/general/vybe/changeRequest/update.deleted_videos_ids.*.required'),
            'deleted_videos_ids.*.integer'                         => trans('validations/api/general/vybe/changeRequest/update.deleted_videos_ids.*.integer'),
            'files.required'                                       => trans('validations/api/general/vybe/changeRequest/update.files.required'),
            'files.array'                                          => trans('validations/api/general/vybe/changeRequest/update.files.array'),
            'files.*.content.required'                             => trans('validations/api/general/vybe/changeRequest/update.files.*.content.required'),
            'files.*.content.string'                               => trans('validations/api/general/vybe/changeRequest/update.files.*.content.string'),
            'files.*.mime.required'                                => trans('validations/api/general/vybe/changeRequest/update.files.*.mime.required'),
            'files.*.mime.string'                                  => trans('validations/api/general/vybe/changeRequest/update.files.*.mime.string'),
            'files.*.extension.required'                           => trans('validations/api/general/vybe/changeRequest/update.files.*.extension.required'),
            'files.*.extension.string'                             => trans('validations/api/general/vybe/changeRequest/update.files.*.extension.string'),
            'files.*.original_name.required'                       => trans('validations/api/general/vybe/changeRequest/update.files.*.original_name.required'),
            'files.*.original_name.string'                         => trans('validations/api/general/vybe/changeRequest/update.files.*.original_name.string'),
            'files.*.main.boolean'                                 => trans('validations/api/general/vybe/changeRequest/update.files.*.main.boolean'),
            // 5-th step
            'access_id.required'                                   => trans('validations/api/general/vybe/changeRequest/update.access_id.required'),
            'access_id.integer'                                    => trans('validations/api/general/vybe/changeRequest/update.access_id.integer'),
            'access_id.between'                                    => trans('validations/api/general/vybe/changeRequest/update.access_id.between'),
            'access_password.string'                               => trans('validations/api/general/vybe/changeRequest/update.access_password.string'),
            'showcase_id.required'                                 => trans('validations/api/general/vybe/changeRequest/update.showcase_id.required'),
            'showcase_id.integer'                                  => trans('validations/api/general/vybe/changeRequest/update.showcase_id.integer'),
            'showcase_id.between'                                  => trans('validations/api/general/vybe/changeRequest/update.showcase_id.between'),
            'age_limit_id.integer'                                 => trans('validations/api/general/vybe/changeRequest/update.age_limit_id.integer'),
            'age_limit_id.between'                                 => trans('validations/api/general/vybe/changeRequest/update.age_limit_id.between'),
            'status_id.required'                                   => trans('validations/api/general/vybe/changeRequest/update.status_id.required'),
            'status_id.integer'                                    => trans('validations/api/general/vybe/changeRequest/update.status_id.integer'),
            'status_id.in'                                         => trans('validations/api/general/vybe/changeRequest/update.status_id.in')
        ];
    }
}
