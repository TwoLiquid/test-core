<?php

namespace App\Http\Requests\Api\Admin\Vybe;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Vybe
 */
class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            // 1-st step
            'title'                                        => 'required|string',
            'category_id'                                  => 'integer|exists:categories,id|nullable',
            'category_suggestion'                          => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'subcategory_id'                               => 'integer|exists:categories,id|nullable',
            'subcategory_suggestion'                       => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'devices_ids'                                  => 'array|nullable',
            'devices_ids.*'                                => 'required|integer|exists:devices,id',
            'device_suggestion'                            => 'string|nullable',
            'activity_id'                                  => 'integer|exists:activities,id|nullable',
            'activity_suggestion'                          => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'period_id'                                    => 'required|integer|between:1,2',
            'user_count'                                   => 'required|integer',
            'suspend_reason'                               => 'string|nullable',
            // 2-nd step
            'appearance_cases'                             => 'required|array',
            'appearance_cases.voice_chat'                  => 'array|nullable',
            'appearance_cases.voice_chat.price'            => 'required|numeric|sometimes',
            'appearance_cases.voice_chat.description'      => 'string|nullable',
            'appearance_cases.voice_chat.unit_id'          => 'integer|exists:units,id|nullable',
            'appearance_cases.voice_chat.unit_suggestion'  => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'appearance_cases.voice_chat.platforms_ids'    => 'array|nullable',
            'appearance_cases.voice_chat.platforms_ids.*'  => 'required|integer|exists:platforms,id|sometimes',
            'appearance_cases.voice_chat.enabled'          => 'required|boolean|sometimes',
            'appearance_cases.video_chat'                  => 'array|nullable',
            'appearance_cases.video_chat.price'            => 'required|numeric|sometimes',
            'appearance_cases.video_chat.description'      => 'string|nullable',
            'appearance_cases.video_chat.unit_id'          => 'integer|exists:units,id|nullable',
            'appearance_cases.video_chat.unit_suggestion'  => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'appearance_cases.video_chat.platforms_ids'    => 'array|nullable',
            'appearance_cases.video_chat.platforms_ids.*'  => 'required|integer|exists:platforms,id|sometimes',
            'appearance_cases.video_chat.enabled'          => 'required|boolean|sometimes',
            'appearance_cases.real_life'                   => 'array|nullable',
            'appearance_cases.real_life.price'             => 'required|numeric|sometimes',
            'appearance_cases.real_life.description'       => 'string|nullable',
            'appearance_cases.real_life.unit_id'           => 'integer|exists:units,id|nullable',
            'appearance_cases.real_life.unit_suggestion'   => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
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
            'order_accept_id'                              => 'required|integer|between:1,2',
            'age_limit_id'                                 => 'required|integer|between:1,3',
            'status_id'                                    => 'required|integer|between:1,5',
            'settings'                                     => 'array|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 1-st step
            'title.required'                                       => trans('validations/api/admin/vybe/update.title.required'),
            'title.string'                                         => trans('validations/api/admin/vybe/update.title.string'),
            'category_id.integer'                                  => trans('validations/api/admin/vybe/update.category_id.integer'),
            'category_id.exists'                                   => trans('validations/api/admin/vybe/update.category_id.exists'),
            'category_suggestion.array'                            => trans('validations/api/admin/vybe/update.category_suggestion.array'),
            'subcategory_id.integer'                               => trans('validations/api/admin/vybe/update.subcategory_id.integer'),
            'subcategory_id.exists'                                => trans('validations/api/admin/vybe/update.subcategory_id.exists'),
            'subcategory_suggestion.array'                         => trans('validations/api/admin/vybe/update.subcategory_suggestion.array'),
            'devices_ids.array'                                    => trans('validations/api/admin/vybe/update.devices_ids.array'),
            'devices_ids.*.required'                               => trans('validations/api/admin/vybe/update.devices_ids.*.required'),
            'devices_ids.*.integer'                                => trans('validations/api/admin/vybe/update.devices_ids.*.integer'),
            'devices_ids.*.exists'                                 => trans('validations/api/admin/vybe/update.devices_ids.*.exists'),
            'device_suggestion.string'                             => trans('validations/api/admin/vybe/update.device_suggestion.string'),
            'activity_id.*.integer'                                => trans('validations/api/admin/vybe/update.activity_id.integer'),
            'activity_id.*.exists'                                 => trans('validations/api/admin/vybe/update.activity_id.exists'),
            'activity_suggestion.array'                            => trans('validations/api/admin/vybe/update.activity_suggestion.array'),
            'period_id.required'                                   => trans('validations/api/admin/vybe/update.period_id.required'),
            'period_id.integer'                                    => trans('validations/api/admin/vybe/update.period_id.integer'),
            'user_count.required'                                  => trans('validations/api/admin/vybe/update.user_count.required'),
            'user_count.integer'                                   => trans('validations/api/admin/vybe/update.user_count.integer'),
            'suspend_reason.string'                                => trans('validations/api/admin/vybe/update.suspend_reason.string'),
            // 2-nd step
            'appearance_cases.required'                            => trans('validations/api/admin/vybe/update.appearance_cases.required'),
            'appearance_cases.array'                               => trans('validations/api/admin/vybe/update.appearance_cases.array'),
            'appearance_cases.voice_chat.array'                    => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.array'),
            'appearance_cases.voice_chat.price.required'           => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.price.required'),
            'appearance_cases.voice_chat.price.numeric'            => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.price.numeric'),
            'appearance_cases.voice_chat.description.string'       => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.description.string'),
            'appearance_cases.voice_chat.unit_id.integer'          => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.unit_id.integer'),
            'appearance_cases.voice_chat.unit_id.exists'           => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.unit_id.exists'),
            'appearance_cases.voice_chat.unit_suggestion.array'    => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.unit_suggestion.array'),
            'appearance_cases.voice_chat.platforms_ids.array'      => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.platforms_ids.array'),
            'appearance_cases.voice_chat.platforms_ids.*.required' => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.platforms_ids.*.required'),
            'appearance_cases.voice_chat.platforms_ids.*.integer'  => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.platforms_ids.*.integer'),
            'appearance_cases.voice_chat.platforms_ids.*.exists'   => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.platforms_ids.*.exists'),
            'appearance_cases.voice_chat.enabled.required'         => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.enabled.required'),
            'appearance_cases.voice_chat.enabled.boolean'          => trans('validations/api/admin/vybe/update.appearance_cases.voice_chat.enabled.boolean'),
            'appearance_cases.video_chat.array'                    => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.array'),
            'appearance_cases.video_chat.price.required'           => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.price.required'),
            'appearance_cases.video_chat.price.numeric'            => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.price.numeric'),
            'appearance_cases.video_chat.description.string'       => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.description.string'),
            'appearance_cases.video_chat.unit_id.integer'          => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.unit_id.integer'),
            'appearance_cases.video_chat.unit_id.exists'           => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.unit_id.exists'),
            'appearance_cases.video_chat.unit_suggestion.array'    => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.unit_suggestion.array'),
            'appearance_cases.video_chat.platforms_ids.array'      => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.platforms_ids.array'),
            'appearance_cases.video_chat.platforms_ids.*.required' => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.platforms_ids.*.required'),
            'appearance_cases.video_chat.platforms_ids.*.integer'  => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.platforms_ids.*.integer'),
            'appearance_cases.video_chat.platforms_ids.*.exists'   => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.platforms_ids.*.exists'),
            'appearance_cases.video_chat.enabled.required'         => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.enabled.required'),
            'appearance_cases.video_chat.enabled.boolean'          => trans('validations/api/admin/vybe/update.appearance_cases.video_chat.enabled.boolean'),
            'appearance_cases.real_life.array'                     => trans('validations/api/admin/vybe/update.appearance_cases.real_life.array'),
            'appearance_cases.real_life.price.required'            => trans('validations/api/admin/vybe/update.appearance_cases.real_life.price.required'),
            'appearance_cases.real_life.price.numeric'             => trans('validations/api/admin/vybe/update.appearance_cases.real_life.price.numeric'),
            'appearance_cases.real_life.description.string'        => trans('validations/api/admin/vybe/update.appearance_cases.real_life.description.string'),
            'appearance_cases.real_life.unit_id.string'            => trans('validations/api/admin/vybe/update.appearance_cases.real_life.unit_id.string'),
            'appearance_cases.real_life.unit_id.exists'            => trans('validations/api/admin/vybe/update.appearance_cases.real_life.unit_id.exists'),
            'appearance_cases.real_life.unit_suggestion.array'     => trans('validations/api/admin/vybe/update.appearance_cases.real_life.unit_suggestion.array'),
            'appearance_cases.real_life.same_location.required'    => trans('validations/api/admin/vybe/update.appearance_cases.real_life.same_location.required'),
            'appearance_cases.real_life.same_location.boolean'     => trans('validations/api/admin/vybe/update.appearance_cases.real_life.same_location.boolean'),
            'appearance_cases.real_life.country_id.integer'        => trans('validations/api/admin/vybe/update.appearance_cases.real_life.country_id.integer'),
            'appearance_cases.real_life.country_id.exists'         => trans('validations/api/admin/vybe/update.appearance_cases.real_life.country_id.exists'),
            'appearance_cases.real_life.region_id.integer'         => trans('validations/api/admin/vybe/update.appearance_cases.real_life.region_id.integer'),
            'appearance_cases.real_life.region_id.exists'          => trans('validations/api/admin/vybe/update.appearance_cases.real_life.region_id.exists'),
            'appearance_cases.real_life.region_suggestion.string'  => trans('validations/api/admin/vybe/update.appearance_cases.real_life.region_suggestion.string'),
            'appearance_cases.real_life.city_id.integer'           => trans('validations/api/admin/vybe/update.appearance_cases.real_life.city_id.integer'),
            'appearance_cases.real_life.city_id.exists'            => trans('validations/api/admin/vybe/update.appearance_cases.real_life.city_id.exists'),
            'appearance_cases.real_life.city_suggestion.string'    => trans('validations/api/admin/vybe/update.appearance_cases.real_life.city_suggestion.string'),
            'appearance_cases.real_life.enabled.required'          => trans('validations/api/admin/vybe/update.appearance_cases.real_life.enabled.required'),
            'appearance_cases.real_life.enabled.boolean'           => trans('validations/api/admin/vybe/update.appearance_cases.real_life.enabled.boolean'),
            // 3-rd step
            'schedules.required'                                   => trans('validations/api/admin/vybe/update.schedules.required'),
            'schedules.array'                                      => trans('validations/api/admin/vybe/update.schedules.array'),
            'schedules.*.datetime_from.required'                   => trans('validations/api/admin/vybe/update.schedules.*.datetime_from.required'),
            'schedules.*.datetime_from.date_format'                => trans('validations/api/admin/vybe/update.schedules.*.datetime_from.date_format'),
            'schedules.*.datetime_to.required'                     => trans('validations/api/admin/vybe/update.schedules.*.datetime_to.required'),
            'schedules.*.datetime_to.date_format'                  => trans('validations/api/admin/vybe/update.schedules.*.datetime_to.date_format'),
            'order_advance.integer'                                => trans('validations/api/admin/vybe/update.order_advance.integer'),
            // 4-th step
            'deleted_images_ids.array'                             => trans('validations/api/admin/vybe/update.deleted_images_ids.array'),
            'deleted_images_ids.*.required'                        => trans('validations/api/admin/vybe/update.deleted_images_ids.*.required'),
            'deleted_images_ids.*.integer'                         => trans('validations/api/admin/vybe/update.deleted_images_ids.*.integer'),
            'deleted_videos_ids.array'                             => trans('validations/api/admin/vybe/update.deleted_videos_ids.array'),
            'deleted_videos_ids.*.required'                        => trans('validations/api/admin/vybe/update.deleted_videos_ids.*.required'),
            'deleted_videos_ids.*.integer'                         => trans('validations/api/admin/vybe/update.deleted_videos_ids.*.integer'),
            'files.array'                                          => trans('validations/api/admin/vybe/update.files.array'),
            'files.*.content.required'                             => trans('validations/api/admin/vybe/update.files.*.content.required'),
            'files.*.content.string'                               => trans('validations/api/admin/vybe/update.files.*.content.string'),
            'files.*.mime.required'                                => trans('validations/api/admin/vybe/update.files.*.mime.required'),
            'files.*.mime.string'                                  => trans('validations/api/admin/vybe/update.files.*.mime.string'),
            'files.*.extension.required'                           => trans('validations/api/admin/vybe/update.files.*.extension.required'),
            'files.*.extension.string'                             => trans('validations/api/admin/vybe/update.files.*.extension.string'),
            'files.*.original_name.required'                       => trans('validations/api/admin/vybe/update.files.*.original_name.required'),
            'files.*.original_name.string'                         => trans('validations/api/admin/vybe/update.files.*.original_name.string'),
            'files.*.main.boolean'                                 => trans('validations/api/admin/vybe/update.files.*.main.boolean'),
            // 5-th step
            'access_id.required'                                   => trans('validations/api/admin/vybe/update.access_id.required'),
            'access_id.integer'                                    => trans('validations/api/admin/vybe/update.access_id.integer'),
            'access_id.between'                                    => trans('validations/api/admin/vybe/update.access_id.between'),
            'access_password.string'                               => trans('validations/api/admin/vybe/update.access_password.string'),
            'showcase_id.required'                                 => trans('validations/api/admin/vybe/update.showcase_id.required'),
            'showcase_id.integer'                                  => trans('validations/api/admin/vybe/update.showcase_id.integer'),
            'showcase_id.between'                                  => trans('validations/api/admin/vybe/update.showcase_id.between'),
            'order_accept_id.required'                             => trans('validations/api/admin/vybe/update.order_accept_id.required'),
            'order_accept_id.integer'                              => trans('validations/api/admin/vybe/update.order_accept_id.integer'),
            'order_accept_id.between'                              => trans('validations/api/admin/vybe/update.order_accept_id.between'),
            'age_limit_id.required'                                => trans('validations/api/admin/vybe/update.age_limit_id.required'),
            'age_limit_id.integer'                                 => trans('validations/api/admin/vybe/update.age_limit_id.integer'),
            'age_limit_id.between'                                 => trans('validations/api/admin/vybe/update.age_limit_id.between'),
            'status_id.required'                                   => trans('validations/api/admin/vybe/update.status_id.required'),
            'status_id.integer'                                    => trans('validations/api/admin/vybe/update.status_id.integer'),
            'status_id.between'                                    => trans('validations/api/admin/vybe/update.status_id.between'),
            // settings
            'settings.array'                                       => trans('validations/api/admin/vybe/update.settings.array')
        ];
    }
}
