<?php

namespace App\Http\Requests\Api\General\Dashboard\Profile;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Username\AlphanumericRule;
use App\Rules\Username\BeginFromLetterRule;
use App\Rules\Username\DoubleUnderscoreRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Profile
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'username'                      => [
                'required',
                'string',
                'min:3',
                'max:25',
                new AlphanumericRule(),
                new BeginFromLetterRule(),
                new DoubleUnderscoreRule()
            ],
            'gender_id'                     => 'required|integer',
            'hide_gender'                   => 'required|boolean',
            'birth_date'                    => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'hide_age'                      => 'required|boolean',
            'current_place_id'              => 'string|nullable',
            'hide_location'                 => 'required|boolean',
            'description'                   => 'string|nullable',
            'top_vybers'                    => 'required|boolean',
            'hide_reviews'                  => 'required|boolean',
            'languages'                     => 'array|nullable',
            'languages.*.language_id'       => 'required|integer',
            'languages.*.language_level_id' => 'required|integer',
            'personality_traits_ids'        => 'array|nullable',
            'personality_traits_ids.*'      => 'required|integer',
            'avatar'                        => 'array|nullable',
            'avatar.content'                => 'string|nullable',
            'avatar.extension'              => 'string|nullable',
            'avatar.mime'                   => 'string|nullable',
            'voice_sample'                  => 'array|nullable',
            'voice_sample.content'          => 'string|nullable',
            'voice_sample.extension'        => 'string|nullable',
            'voice_sample.mime'             => 'string|nullable',
            'background'                    => 'array|nullable',
            'background.content'            => 'string|nullable',
            'background.extension'          => 'string|nullable',
            'background.mime'               => 'string|nullable',
            'album'                         => 'array|nullable',
            'album.*.content'               => 'required|string',
            'album.*.extension'             => 'required|string',
            'album.*.mime'                  => 'required|string',
            'deleted_avatar_id'             => 'integer|nullable',
            'deleted_voice_sample_id'       => 'integer|nullable',
            'deleted_background_id'         => 'integer|nullable',
            'deleted_images_ids'            => 'array|nullable',
            'deleted_images_ids.*'          => 'integer|nullable',
            'deleted_videos_ids'            => 'array|nullable',
            'deleted_videos.*'              => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'username.required'                      => trans('validations/api/general/dashboard/profile/update.username.required'),
            'username.string'                        => trans('validations/api/general/dashboard/profile/update.username.string'),
            'gender_id.required'                     => trans('validations/api/general/dashboard/profile/update.gender_id.required'),
            'gender_id.integer'                      => trans('validations/api/general/dashboard/profile/update.gender_id.integer'),
            'hide_gender.required'                   => trans('validations/api/general/dashboard/profile/update.hide_gender.required'),
            'hide_gender.boolean'                    => trans('validations/api/general/dashboard/profile/update.hide_gender.boolean'),
            'birth_date.required'                    => trans('validations/api/general/dashboard/profile/update.birth_date.required'),
            'birth_date.string'                      => trans('validations/api/general/dashboard/profile/update.birth_date.string'),
            'hide_age.required'                      => trans('validations/api/general/dashboard/profile/update.hide_age.required'),
            'hide_age.boolean'                       => trans('validations/api/general/dashboard/profile/update.hide_age.boolean'),
            'current_place_id.string'                => trans('validations/api/general/dashboard/profile/update.current_place_id.string'),
            'hide_location.required'                 => trans('validations/api/general/dashboard/profile/update.hide_gender.required'),
            'hide_location.boolean'                  => trans('validations/api/general/dashboard/profile/update.hide_gender.boolean'),
            'description.string'                     => trans('validations/api/general/dashboard/profile/update.description.string'),
            'top_vybers.required'                    => trans('validations/api/general/dashboard/profile/update.top_vybers.required'),
            'top_vybers.boolean'                     => trans('validations/api/general/dashboard/profile/update.top_vybers.boolean'),
            'hide_reviews.required'                  => trans('validations/api/general/dashboard/profile/update.top_vybers.required'),
            'hide_reviews.boolean'                   => trans('validations/api/general/dashboard/profile/update.top_vybers.boolean'),
            'languages.array'                        => trans('validations/api/general/dashboard/profile/update.languages.array'),
            'languages.*.language_id.required'       => trans('validations/api/general/dashboard/profile/update.languages.*.language_id.required'),
            'languages.*.language_id.integer'        => trans('validations/api/general/dashboard/profile/update.languages.*.language_id.integer'),
            'languages.*.language_level_id.required' => trans('validations/api/general/dashboard/profile/update.languages.*.language_level_id.required'),
            'languages.*.language_level_id.integer'  => trans('validations/api/general/dashboard/profile/update.languages.*.language_level_id.integer'),
            'personality_traits_ids.array'           => trans('validations/api/general/dashboard/profile/update.personality_traits_ids.array'),
            'personality_traits_ids.*.required'      => trans('validations/api/general/dashboard/profile/update.personality_traits_ids.*.required'),
            'personality_traits_ids.*.integer'       => trans('validations/api/general/dashboard/profile/update.personality_traits_ids.*.integer'),
            'avatar.array'                           => trans('validations/api/general/dashboard/profile/update.avatar.array'),
            'avatar.content.string'                  => trans('validations/api/general/dashboard/profile/update.avatar.content.string'),
            'avatar.extension.string'                => trans('validations/api/general/dashboard/profile/update.avatar.extension.string'),
            'avatar.mime.string'                     => trans('validations/api/general/dashboard/profile/update.avatar.mime.string'),
            'voice_sample.array'                     => trans('validations/api/general/dashboard/profile/update.voice_sample.array'),
            'voice_sample.content.string'            => trans('validations/api/general/dashboard/profile/update.voice_sample.content.string'),
            'voice_sample.extension.string'          => trans('validations/api/general/dashboard/profile/update.voice_sample.extension.string'),
            'voice_sample.mime.string'               => trans('validations/api/general/dashboard/profile/update.voice_sample.mime.string'),
            'background.array'                       => trans('validations/api/general/dashboard/profile/update.background.array'),
            'background.content.string'              => trans('validations/api/general/dashboard/profile/update.background.content.string'),
            'background.extension.string'            => trans('validations/api/general/dashboard/profile/update.background.extension.string'),
            'background.mime.string'                 => trans('validations/api/general/dashboard/profile/update.background.mime.string'),
            'album.array'                            => trans('validations/api/general/dashboard/profile/update.album.array'),
            'album.*.content.required'               => trans('validations/api/general/dashboard/profile/update.album.*.content.required'),
            'album.*.content.string'                 => trans('validations/api/general/dashboard/profile/update.album.*.content.string'),
            'album.*.extension.required'             => trans('validations/api/general/dashboard/profile/update.album.*.extension.required'),
            'album.*.extension.string'               => trans('validations/api/general/dashboard/profile/update.album.*.extension.string'),
            'album.*.mime.required'                  => trans('validations/api/general/dashboard/profile/update.album.*.mime.required'),
            'album.*.mime.string'                    => trans('validations/api/general/dashboard/profile/update.album.*.mime.string'),
            'deleted_avatar_id.integer'              => trans('validations/api/general/dashboard/profile/update.deleted_avatar_id.integer'),
            'deleted_voice_sample_id.integer'        => trans('validations/api/general/dashboard/profile/update.deleted_voice_sample_id.integer'),
            'deleted_background_id.integer'          => trans('validations/api/general/dashboard/profile/update.deleted_background_id.integer'),
            'deleted_images_ids.array'               => trans('validations/api/general/dashboard/profile/update.deleted_images_ids.array'),
            'deleted_images_ids.*.integer'           => trans('validations/api/general/dashboard/profile/update.deleted_images_ids.*.integer'),
            'deleted_videos_ids.array'               => trans('validations/api/general/dashboard/profile/update.deleted_videos_ids.array'),
            'deleted_videos_ids.*.integer'           => trans('validations/api/general/dashboard/profile/update.deleted_videos_ids.*.integer')
        ];
    }
}
