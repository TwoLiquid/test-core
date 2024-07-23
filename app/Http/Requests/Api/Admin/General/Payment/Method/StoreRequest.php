<?php

namespace App\Http\Requests\Api\Admin\General\Payment\Method;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Payment\Method
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'payment_status_id'    => 'required|integer|between:1,3',
            'withdrawal_status_id' => 'required|integer|between:1,3',
            'name'                 => 'required|string',
            'payment_fee'          => 'required|numeric',
            'order_form'           => 'required|boolean',
            'integrated'           => 'required|boolean',
            'display_name'         => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'duration_title'       => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'duration_amount'      => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'fee_title'            => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'fee_amount'           => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'country_places_ids'            => 'array|nullable',
            'country_places_ids.*'          => 'required|string|exists:country_places,place_id',
            'excluded_country_places_ids'   => 'array|nullable',
            'excluded_country_places_ids.*' => 'required|string|exists:country_places,place_id',
            'fields'                        => 'required|array',
            'fields.*.type_id'              => 'required|integer|between:1,3',
            'fields.*.title'       => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'fields.*.placeholder' => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'fields.*.tooltip' => [
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule(),
                'nullable'
            ],
            'image'                => 'required|array',
            'image.content'        => 'required|string',
            'image.extension'      => 'required|string',
            'image.mime'           => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'payment_status_id.required'             => trans('validations/api/admin/general/payment/method/store.payment_status_id.required'),
            'payment_status_id.integer'              => trans('validations/api/admin/general/payment/method/store.payment_status_id.integer'),
            'payment_status_id.between'              => trans('validations/api/admin/general/payment/method/store.payment_status_id.between'),
            'withdrawal_status_id.required'          => trans('validations/api/admin/general/payment/method/store.withdrawal_status_id.required'),
            'withdrawal_status_id.integer'           => trans('validations/api/admin/general/payment/method/store.withdrawal_status_id.integer'),
            'withdrawal_status_id.between'           => trans('validations/api/admin/general/payment/method/store.withdrawal_status_id.between'),
            'name.required'                          => trans('validations/api/admin/general/payment/method/store.name.required'),
            'name.string'                            => trans('validations/api/admin/general/payment/method/store.name.string'),
            'payment_fee.required'                   => trans('validations/api/admin/general/payment/method/store.payment_fee.required'),
            'payment_fee.numeric'                    => trans('validations/api/admin/general/payment/method/store.payment_fee.numeric'),
            'order_form.required'                    => trans('validations/api/admin/general/payment/method/store.order_form.required'),
            'order_form.boolean'                     => trans('validations/api/admin/general/payment/method/store.order_form.boolean'),
            'integrated.required'                    => trans('validations/api/admin/general/payment/method/store.integrated.required'),
            'integrated.boolean'                     => trans('validations/api/admin/general/payment/method/store.integrated.boolean'),
            'display_name.required'                  => trans('validations/api/admin/general/payment/method/store.display_name.required'),
            'display_name.array'                     => trans('validations/api/admin/general/payment/method/store.display_name.array'),
            'duration_title.required'                => trans('validations/api/admin/general/payment/method/store.duration_title.required'),
            'duration_title.array'                   => trans('validations/api/admin/general/payment/method/store.duration_title.array'),
            'duration_amount.required'               => trans('validations/api/admin/general/payment/method/store.duration_amount.required'),
            'duration_amount.array'                  => trans('validations/api/admin/general/payment/method/store.duration_amount.array'),
            'fee_title.required'                     => trans('validations/api/admin/general/payment/method/store.fee_title.required'),
            'fee_title.array'                        => trans('validations/api/admin/general/payment/method/store.fee_title.array'),
            'fee_amount.required'                    => trans('validations/api/admin/general/payment/method/store.fee_amount.required'),
            'fee_amount.array'                       => trans('validations/api/admin/general/payment/method/store.fee_amount.array'),
            'country_places_ids.required'            => trans('validations/api/admin/general/payment/method/store.country_places_ids.required'),
            'country_places_ids.array'               => trans('validations/api/admin/general/payment/method/store.country_places_ids.array'),
            'country_places_ids.*.required'          => trans('validations/api/admin/general/payment/method/store.country_places_ids.*.required'),
            'country_places_ids.*.string'            => trans('validations/api/admin/general/payment/method/store.country_places_ids.*.string'),
            'country_places_ids.*.exists'            => trans('validations/api/admin/general/payment/method/store.country_places_ids.*.exists'),
            'excluded_country_places_ids.required'   => trans('validations/api/admin/general/payment/method/store.excluded_country_places_ids.required'),
            'excluded_country_places_ids.array'      => trans('validations/api/admin/general/payment/method/store.excluded_country_places_ids.array'),
            'excluded_country_places_ids.*.required' => trans('validations/api/admin/general/payment/method/store.excluded_country_places_ids.*.required'),
            'excluded_country_places_ids.*.string'   => trans('validations/api/admin/general/payment/method/store.excluded_country_places_ids.*.string'),
            'excluded_country_places_ids.*.exists'   => trans('validations/api/admin/general/payment/method/store.excluded_country_places_ids.*.exists'),
            'fields.required'                        => trans('validations/api/admin/general/payment/method/store.fields.required'),
            'fields.array'                           => trans('validations/api/admin/general/payment/method/store.fields.array'),
            'fields.*.type_id.required'              => trans('validations/api/admin/general/payment/method/store.fields.*.type_id.required'),
            'fields.*.type_id.string'                => trans('validations/api/admin/general/payment/method/store.fields.*.type_id.string'),
            'fields.*.type_id.between'               => trans('validations/api/admin/general/payment/method/store.fields.*.type_id.between'),
            'fields.*.title.required'                => trans('validations/api/admin/general/payment/method/store.fields.*.title.required'),
            'fields.*.title.array'                   => trans('validations/api/admin/general/payment/method/store.fields.*.title.array'),
            'fields.*.placeholder.required'          => trans('validations/api/admin/general/payment/method/store.fields.*.placeholder.required'),
            'fields.*.placeholder.array'             => trans('validations/api/admin/general/payment/method/store.fields.*.placeholder.array'),
            'fields.*.tooltip.array'                 => trans('validations/api/admin/general/payment/method/store.fields.*.tooltip.array'),
            'image.required'                         => trans('validations/api/admin/general/payment/method/store.image.required'),
            'image.array'                            => trans('validations/api/admin/general/payment/method/store.image.array'),
            'image.content.required'                 => trans('validations/api/admin/general/payment/method/store.image.content.required'),
            'image.content.string'                   => trans('validations/api/admin/general/payment/method/store.image.content.string'),
            'image.extension.required'               => trans('validations/api/admin/general/payment/method/store.image.extension.required'),
            'image.extension.string'                 => trans('validations/api/admin/general/payment/method/store.image.extension.string'),
            'image.mime.required'                    => trans('validations/api/admin/general/payment/method/store.image.mime.required'),
            'image.mime.string'                      => trans('validations/api/admin/general/payment/method/store.image.mime.string')
        ];
    }
}
