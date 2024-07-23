<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api General Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'type_id' => [
        'integer' => __('validations.api.general.alert.update.type_id.integer'),
        'between' => __('validations.api.general.alert.update.type_id.between')
    ],
    'animation_id' => [
        'integer' => __('validations.api.general.alert.update.animation_id.integer'),
        'between' => __('validations.api.general.alert.update.animation_id.between')
    ],
    'align_id' => [
        'integer' => __('validations.api.general.alert.update.align_id.integer'),
        'between' => __('validations.api.general.alert.update.align_id.between')
    ],
    'text_font_id' => [
        'integer' => __('validations.api.general.alert.update.text_font_id.integer'),
        'between' => __('validations.api.general.alert.update.text_font_id.between')
    ],
    'text_style_id' => [
        'integer' => __('validations.api.general.alert.update.text_style_id.integer'),
        'between' => __('validations.api.general.alert.update.text_style_id.between')
    ],
    'logo_align_id' => [
        'integer' => __('validations.api.general.alert.update.logo_align_id.integer'),
        'between' => __('validations.api.general.alert.update.logo_align_id.between')
    ],
    'cover_id' => [
        'integer' => __('validations.api.general.alert.update.cover_id.integer')
    ],
    'duration' => [
        'integer' => __('validations.api.general.alert.update.duration.integer')
    ],
    'text_font_color' => [
        'string' => __('validations.api.general.alert.update.text_font_color.string')
    ],
    'text_font_size' => [
        'integer' => __('validations.api.general.alert.update.text_font_size.integer')
    ],
    'logo_color' => [
        'string' => __('validations.api.general.alert.update.logo_color.string')
    ],
    'volume' => [
        'integer' => __('validations.api.general.alert.update.volume.integer')
    ],
    'username' => [
        'integer' => __('validations.api.general.alert.update.username.integer')
    ],
    'amount' => [
        'string' => __('validations.api.general.alert.update.amount.string')
    ],
    'action' => [
        'string' => __('validations.api.general.alert.update.action.string')
    ],
    'message' => [
        'string' => __('validations.api.general.alert.update.message.string')
    ],
    'profanity_filter' => [
        'replace' => [
            'required' => __('validations.api.general.alert.update.profanity_filter.replace.required'),
            'boolean'  => __('validations.api.general.alert.update.profanity_filter.replace.boolean')
        ],
        'replace_with' => [
            'required' => __('validations.api.general.alert.update.profanity_filter.replace_with.required'),
            'string'   => __('validations.api.general.alert.update.profanity_filter.replace_with.string')
        ],
        'hide_messages' => [
            'required' => __('validations.api.general.alert.update.profanity_filter.hide_messages.required'),
            'boolean'  => __('validations.api.general.alert.update.profanity_filter.hide_messages.boolean')
        ],
        'bad_words' => [
            'required' => __('validations.api.general.alert.update.profanity_filter.bad_words.required'),
            'boolean'  => __('validations.api.general.alert.update.profanity_filter.bad_words.boolean')
        ],
        'words' => [
            'array'  => __('validations.api.general.alert.update.profanity_filter.words.array'),
            '*' => [
                'required' => __('validations.api.general.alert.update.profanity_filter.words.*.required'),
                'string'   => __('validations.api.general.alert.update.profanity_filter.words.*.string')
            ]
        ]
    ],
    'widget_url' => [
        'string' => __('validations.api.general.alert.update.widget_url.string')
    ],
    'images' => [
        'array'    => __('validations.api.general.alert.update.images.array'),
        'content'  => [
            'required' => __('validations.api.general.alert.update.images.content.required'),
            'string'   => __('validations.api.general.alert.update.images.content.string')
        ],
        'extension' => [
            'required' => __('validations.api.general.alert.update.images.extension.required'),
            'string'   => __('validations.api.general.alert.update.images.extension.string')
        ],
        'mime' => [
            'required' => __('validations.api.general.alert.update.images.mime.required'),
            'string'   => __('validations.api.general.alert.update.images.mime.string')
        ]
    ],
    'sounds' => [
        'array'    => __('validations.api.general.alert.update.sounds.array'),
        'content'  => [
            'required' => __('validations.api.general.alert.update.images.content.required'),
            'string'   => __('validations.api.general.alert.update.images.content.string')
        ],
        'extension' => [
            'required' => __('validations.api.general.alert.update.images.extension.required'),
            'string'   => __('validations.api.general.alert.update.images.extension.string')
        ],
        'mime' => [
            'required' => __('validations.api.general.alert.update.images.mime.required'),
            'string'   => __('validations.api.general.alert.update.images.mime.string')
        ]
    ],
    'deleted_images_ids' => [
        'array' => __('validations.api.general.alert.update.deleted_images_ids.array'),
        '*'  => [
            'required' => __('validations.api.general.alert.update.deleted_images_ids.*.required'),
            'integer'  => __('validations.api.general.alert.update.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_sounds_ids' => [
        'array' => __('validations.api.general.alert.update.deleted_sounds_ids.array'),
        '*'  => [
            'required' => __('validations.api.general.alert.update.deleted_sounds_ids.*.required'),
            'integer'  => __('validations.api.general.alert.update.deleted_sounds_ids.*.integer')
        ]
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.general.alert.update.result.error.find'),
            'update' => __('validations.api.general.alert.update.result.error.update')
        ],
        'success' => __('validations.api.general.alert.update.result.success')
    ]
];