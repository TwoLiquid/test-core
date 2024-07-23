<?php

namespace App\Http\Requests\Api\General\Alert;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Alert
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'type_id'                        => 'integer|between:1,2|nullable',
            'animation_id'                   => 'integer|between:1,13|nullable',
            'align_id'                       => 'integer|between:1,3|nullable',
            'text_font_id'                   => 'integer|between:1,9|nullable',
            'text_style_id'                  => 'integer|between:1,4|nullable',
            'logo_align_id'                  => 'integer|between:1,3|nullable',
            'cover_id'                       => 'integer|nullable',
            'duration'                       => 'integer|nullable',
            'text_font_color'                => 'string|nullable',
            'text_font_size'                 => 'integer|nullable',
            'logo_color'                     => 'string|nullable',
            'volume'                         => 'integer|nullable',
            'username'                       => 'string|nullable',
            'amount'                         => 'integer|nullable',
            'action'                         => 'string|nullable',
            'message'                        => 'string|nullable',
            'profanity_filter'               => 'array|nullable',
            'profanity_filter.replace'       => 'required|boolean',
            'profanity_filter.replace_with'  => 'string|nullable',
            'profanity_filter.hide_messages' => 'required|boolean',
            'profanity_filter.bad_words'     => 'required|boolean',
            'profanity_filter.words'         => 'array|nullable',
            'profanity_filter.words.*'       => 'required|string',
            'widget_url'                     => 'string|nullable',
            'images'                         => 'array|nullable',
            'images.*.content'               => 'required|string',
            'images.*.extension'             => 'required|string',
            'images.*.mime'                  => 'required|string',
            'sounds'                         => 'array|nullable',
            'sounds.*.content'               => 'required|string',
            'sounds.*.extension'             => 'required|string',
            'sounds.*.mime'                  => 'required|string',
            'deleted_images_ids'             => 'array|nullable',
            'deleted_images_ids.*'           => 'required|integer',
            'deleted_sounds_ids'             => 'array|nullable',
            'deleted_sounds_ids.*'           => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'type_id.integer'                         => trans('validations/api/general/alert/update.type_id.integer'),
            'animation_id.integer'                    => trans('validations/api/general/alert/update.animation_id.integer'),
            'animation_id.between'                    => trans('validations/api/general/alert/update.animation_id.between'),
            'align_id.integer'                        => trans('validations/api/general/alert/update.align_id.integer'),
            'align_id.between'                        => trans('validations/api/general/alert/update.align_id.between'),
            'text_font_id.integer'                    => trans('validations/api/general/alert/update.text_font_id.integer'),
            'text_font_id.between'                    => trans('validations/api/general/alert/update.text_font_id.between'),
            'text_style_id.integer'                   => trans('validations/api/general/alert/update.text_style_id.integer'),
            'text_style_id.between'                   => trans('validations/api/general/alert/update.text_style_id.between'),
            'logo_align_id.integer'                   => trans('validations/api/general/alert/update.logo_align_id.integer'),
            'logo_align_id.between'                   => trans('validations/api/general/alert/update.logo_align_id.between'),
            'cover_id.integer'                        => trans('validations/api/general/alert/update.cover_id.integer'),
            'duration.integer'                        => trans('validations/api/general/alert/update.duration.integer'),
            'text_font_color.string'                  => trans('validations/api/general/alert/update.text_font_color.string'),
            'text_font_size.integer'                  => trans('validations/api/general/alert/update.text_font_size.integer'),
            'logo_color.string'                       => trans('validations/api/general/alert/update.logo_color.string'),
            'volume.integer'                          => trans('validations/api/general/alert/update.volume.integer'),
            'username.string'                         => trans('validations/api/general/alert/update.username.string'),
            'amount.integer'                          => trans('validations/api/general/alert/update.amount.integer'),
            'action.integer'                          => trans('validations/api/general/alert/update.action.integer'),
            'message.string'                          => trans('validations/api/general/alert/update.message.string'),
            'profanity_filter.array'                  => trans('validations/api/general/alert/update.profanity_filter.array'),
            'profanity_filter.replace.required'       => trans('validations/api/general/alert/update.profanity_filter.replace.required'),
            'profanity_filter.replace.boolean'        => trans('validations/api/general/alert/update.profanity_filter.replace.boolean'),
            'profanity_filter.replace_with.string'    => trans('validations/api/general/alert/update.profanity_filter.replace_with.string'),
            'profanity_filter.hide_messages.required' => trans('validations/api/general/alert/update.profanity_filter.hide_messages.required'),
            'profanity_filter.hide_messages.boolean'  => trans('validations/api/general/alert/update.profanity_filter.hide_messages.boolean'),
            'profanity_filter.bad_words.required'     => trans('validations/api/general/alert/update.profanity_filter.bad_words.required'),
            'profanity_filter.bad_words.boolean'      => trans('validations/api/general/alert/update.profanity_filter.bad_words.boolean'),
            'profanity_filter.words.array'            => trans('validations/api/general/alert/update.profanity_filter.words.array'),
            'profanity_filter.words.*.required'       => trans('validations/api/general/alert/update.profanity_filter.*.words.required'),
            'profanity_filter.words.*.string'         => trans('validations/api/general/alert/update.profanity_filter.*.words.array'),
            'widget_url.string'                       => trans('validations/api/general/alert/update.widget_url.string'),
            'images.array'                            => trans('validations/api/general/alert/update.images.array'),
            'images.*.content.required'               => trans('validations/api/general/alert/update.images.*.content.required'),
            'images.*.content.string'                 => trans('validations/api/general/alert/update.images.*.content.string'),
            'images.*.extension.required'             => trans('validations/api/general/alert/update.images.*.extension.required'),
            'images.*.extension.string'               => trans('validations/api/general/alert/update.images.*.extension.string'),
            'images.*.mime.required'                  => trans('validations/api/general/alert/update.images.*.mime.required'),
            'images.*.mime.string'                    => trans('validations/api/general/alert/update.images.*.mime.string'),
            'sounds.array'                            => trans('validations/api/general/alert/update.sounds.array'),
            'sounds.*.content.required'               => trans('validations/api/general/alert/update.sounds.*.content.required'),
            'sounds.*.content.string'                 => trans('validations/api/general/alert/update.sounds.*.content.string'),
            'sounds.*.extension.required'             => trans('validations/api/general/alert/update.sounds.*.extension.required'),
            'sounds.*.extension.string'               => trans('validations/api/general/alert/update.sounds.*.extension.string'),
            'sounds.*.mime.required'                  => trans('validations/api/general/alert/update.sounds.*.mime.required'),
            'sounds.*.mime.string'                    => trans('validations/api/general/alert/update.sounds.*.mime.string'),
            'deleted_images_ids.array'                => trans('validations/api/general/alert/update.deleted_images_ids.array'),
            'deleted_images_ids.*.integer'            => trans('validations/api/general/alert/update.deleted_images_ids.*.integer'),
            'deleted_sounds_ids.array'                => trans('validations/api/general/alert/update.deleted_sounds_ids.array'),
            'deleted_sounds_ids.*.integer'            => trans('validations/api/general/alert/update.deleted_sounds_ids.*.integer')
        ];
    }
}
