<?php

namespace App\Http\Requests\Api\Admin\User\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\User
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'account_status_id'             => 'required|integer',
            'buyer_status_id'               => 'required|integer',
            'seller_status_id'              => 'required|integer',
            'affiliate_status_id'           => 'required|integer',
            'language_id'                   => 'required|integer',
            'currency_id'                   => 'required|integer',
            'timezone_city_place_id'        => 'string|nullable',
            'username'                      => 'required|string',
            'email'                         => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix',
            'password'                      => 'string|nullable',
            'gender_id'                     => 'required|integer',
            'hide_gender'                   => 'required|boolean',
            'birth_date'                    => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'hide_age'                      => 'required|boolean',
            'verified_partner'              => 'required|boolean',
            'streamer_badge'                => 'required|boolean',
            'streamer_milestone'            => 'required|boolean',
            'current_city_place_id'         => 'string|nullable',
            'hide_location'                 => 'required|boolean',
            'languages'                     => 'array|nullable',
            'languages.*.language_id'       => 'required|integer',
            'languages.*.language_level_id' => 'required|integer',
            'personality_traits_ids'        => 'array|nullable',
            'personality_traits_ids.*'      => 'required|integer',
            'description'                   => 'string|nullable',
            'suspend_reason'                => 'string|nullable',
            'avatar'                        => 'array|nullable',
            'avatar.content'                => 'string|nullable',
            'avatar.extension'              => 'string|nullable',
            'avatar.mime'                   => 'string|nullable',
            'background'                    => 'array|nullable',
            'background.content'            => 'string|nullable',
            'background.extension'          => 'string|nullable',
            'background.mime'               => 'string|nullable',
            'album'                         => 'array|nullable',
            'album.*.content'               => 'required|string',
            'album.*.extension'             => 'required|string',
            'album.*.mime'                  => 'required|string',
            'voice_sample'                  => 'array|nullable',
            'voice_sample.content'          => 'string|nullable',
            'voice_sample.extension'        => 'string|nullable',
            'voice_sample.mime'             => 'string|nullable',
            'receive_news'                  => 'required|boolean',
            'deleted_images_ids'            => 'array|nullable',
            'deleted_images_ids.*'          => 'integer|nullable',
            'deleted_videos_ids'            => 'array|nullable',
            'deleted_videos_ids.*'          => 'integer|nullable',
            'deleted_avatar_id'             => 'integer|nullable',
            'deleted_background_id'         => 'integer|nullable',
            'deleted_voice_sample_id'       => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'account_status_id.required'             => trans('validations/api/admin/user/user/update.account_status_id.required'),
            'account_status_id.integer'              => trans('validations/api/admin/user/user/update.account_status_id.integer'),
            'buyer_status_id.required'               => trans('validations/api/admin/user/user/update.buyer_status_id.required'),
            'buyer_status_id.integer'                => trans('validations/api/admin/user/user/update.buyer_status_id.integer'),
            'seller_status_id.required'              => trans('validations/api/admin/user/user/update.seller_status_id.required'),
            'seller_status_id.integer'               => trans('validations/api/admin/user/user/update.seller_status_id.integer'),
            'affiliate_status_id.required'           => trans('validations/api/admin/user/user/update.affiliate_status_id.required'),
            'affiliate_status_id.integer'            => trans('validations/api/admin/user/user/update.affiliate_status_id.integer'),
            'language_id.required'                   => trans('validations/api/admin/user/user/update.language_id.required'),
            'language_id.integer'                    => trans('validations/api/admin/user/user/update.language_id.integer'),
            'currency_id.required'                   => trans('validations/api/admin/user/user/update.currency_id.required'),
            'currency_id.integer'                    => trans('validations/api/admin/user/user/update.currency_id.integer'),
            'timezone_city_place_id.required'        => trans('validations/api/admin/user/user/update.timezone_city_place_id.required'),
            'timezone_city_place_id.string'          => trans('validations/api/admin/user/user/update.timezone_city_place_id.string'),
            'username.required'                      => trans('validations/api/admin/user/user/update.username.required'),
            'username.string'                        => trans('validations/api/admin/user/user/update.username.string'),
            'email.required'                         => trans('validations/api/admin/user/user/update.email.required'),
            'email.regex'                            => trans('validations/api/admin/user/user/update.email.regex'),
            'email.unique'                           => trans('validations/api/admin/user/user/update.email.unique'),
            'password.required'                      => trans('validations/api/admin/user/user/update.password.required'),
            'password.string'                        => trans('validations/api/admin/user/user/update.password.string'),
            'gender_id.required'                     => trans('validations/api/admin/user/user/update.gender_id.required'),
            'gender_id.integer'                      => trans('validations/api/admin/user/user/update.gender_id.integer'),
            'hide_gender.required'                   => trans('validations/api/admin/user/user/update.hide_gender.required'),
            'hide_gender.boolean'                    => trans('validations/api/admin/user/user/update.hide_gender.boolean'),
            'birth_date.required'                    => trans('validations/api/admin/user/user/update.birth_date.required'),
            'birth_date.string'                      => trans('validations/api/admin/user/user/update.birth_date.string'),
            'hide_age.required'                      => trans('validations/api/admin/user/user/update.hide_age.required'),
            'hide_age.boolean'                       => trans('validations/api/admin/user/user/update.hide_age.boolean'),
            'verified_partner.required'              => trans('validations/api/admin/user/user/update.verified_partner.required'),
            'verified_partner.boolean'               => trans('validations/api/admin/user/user/update.verified_partner.boolean'),
            'streamer_badge.required'                => trans('validations/api/admin/user/user/update.streamer_badge.required'),
            'streamer_badge.boolean'                 => trans('validations/api/admin/user/user/update.streamer_badge.boolean'),
            'streamer_milestone.required'            => trans('validations/api/admin/user/user/update.streamer_milestone.required'),
            'streamer_milestone.boolean'             => trans('validations/api/admin/user/user/update.streamer_milestone.boolean'),
            'current_city_place_id.required'         => trans('validations/api/admin/user/user/update.current_city_place_id.required'),
            'current_city_place_id.string'           => trans('validations/api/admin/user/user/update.current_city_place_id.string'),
            'hide_location.required'                 => trans('validations/api/admin/user/user/update.hide_location.required'),
            'hide_location.boolean'                  => trans('validations/api/admin/user/user/update.hide_location.boolean'),
            'languages.array'                        => trans('validations/api/admin/user/user/update.languages.array'),
            'languages.*.language_id.required'       => trans('validations/api/admin/user/user/update.languages.*.language_id.required'),
            'languages.*.language_id.integer'        => trans('validations/api/admin/user/user/update.languages.*.language_id.integer'),
            'languages.*.language_level_id.required' => trans('validations/api/admin/user/user/update.languages.*.language_level_id.required'),
            'languages.*.language_level_id.integer'  => trans('validations/api/admin/user/user/update.languages.*.language_level_id.integer'),
            'personality_traits_ids.array'           => trans('validations/api/admin/user/user/update.personality_traits_ids.array'),
            'personality_traits_ids.*.required'      => trans('validations/api/admin/user/user/update.personality_traits_ids.*.required'),
            'personality_traits_ids.*.integer'       => trans('validations/api/admin/user/user/update.personality_traits_ids.*.integer'),
            'description.string'                     => trans('validations/api/admin/user/user/update.description.string'),
            'suspend_reason.string'                  => trans('validations/api/admin/user/user/update.suspend_reason.string'),
            'avatar.array'                           => trans('validations/api/admin/user/user/update.avatar.array'),
            'avatar.content.string'                  => trans('validations/api/admin/user/user/update.avatar.content.string'),
            'avatar.extension.string'                => trans('validations/api/admin/user/user/update.avatar.extension.string'),
            'avatar.mime.string'                     => trans('validations/api/admin/user/user/update.avatar.mime.string'),
            'album.array'                            => trans('validations/api/admin/user/user/update.album.array'),
            'album.*.content.required'               => trans('validations/api/admin/user/user/update.album.*.content.required'),
            'album.*.content.string'                 => trans('validations/api/admin/user/user/update.album.*.content.string'),
            'album.*.extension.required'             => trans('validations/api/admin/user/user/update.album.*.extension.required'),
            'album.*.extension.string'               => trans('validations/api/admin/user/user/update.album.*.extension.string'),
            'album.*.mime.required'                  => trans('validations/api/admin/user/user/update.album.*.mime.required'),
            'album.*.mime.string'                    => trans('validations/api/admin/user/user/update.album.*.mime.string'),
            'voice_sample.array'                     => trans('validations/api/admin/user/user/update.voice_sample.array'),
            'voice_sample.content.string'            => trans('validations/api/admin/user/user/update.voice_sample.content.string'),
            'voice_sample.extension.string'          => trans('validations/api/admin/user/user/update.voice_sample.extension.string'),
            'voice_sample.mime.string'               => trans('validations/api/admin/user/user/update.voice_sample.mime.string'),
            'receive_news.required'                  => trans('validations/api/admin/user/user/update.receive_news.required'),
            'receive_news.boolean'                   => trans('validations/api/admin/user/user/update.receive_news.boolean'),
            'deleted_images_ids.array'               => trans('validations/api/admin/user/user/update.deleted_images_ids.array'),
            'deleted_images_ids.*.integer'           => trans('validations/api/admin/user/user/update.deleted_images_ids.*.integer'),
            'deleted_videos_ids.array'               => trans('validations/api/admin/user/user/update.deleted_videos_ids.array'),
            'deleted_videos_ids.*.integer'           => trans('validations/api/admin/user/user/update.deleted_videos_ids.*.integer'),
            'deleted_avatar_id.integer'              => trans('validations/api/admin/user/user/update.deleted_avatar_id.integer'),
            'deleted_background_id.integer'          => trans('validations/api/admin/user/user/update.deleted_background_id.integer'),
            'deleted_voice_sample_id.integer'        => trans('validations/api/admin/user/user/update.deleted_voice_sample_id.integer')
        ];
    }
}
