<?php

namespace App\Http\Requests\Api\Guest\Auth;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Username\AlphanumericRule;
use App\Rules\Username\BeginFromLetterRule;
use App\Rules\Username\DoubleUnderscoreRule;

/**
 * Class RegisterRequest
 *
 * @package App\Http\Requests\Api\Guest\Auth
 */
class RegisterRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'username'         => [
                'required',
                'string',
                'min:3',
                'max:25',
                'unique:users,username',
                new AlphanumericRule(),
                new BeginFromLetterRule(),
                new DoubleUnderscoreRule()
            ],
            'email'            => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix',
            'gender_id'        => 'required|integer',
            'birth_date'       => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'password'         => 'required|string',
            'password_confirm' => 'required|string',
            'hide_gender'      => 'boolean|nullable',
            'hide_age'         => 'boolean|nullable',
            'country_place_id' => 'required|string|exists:country_places,place_id',
            'region_place_id'  => 'string|exists:region_places,place_id|nullable',
            'invitation_code'  => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'username.required'         => trans('validations/api/guest/auth/register.username.required'),
            'username.string'           => trans('validations/api/guest/auth/register.username.string'),
            'username.regex'            => trans('validations/api/guest/auth/register.username.regex'),
            'username.min'              => trans('validations/api/guest/auth/register.username.min'),
            'username.max'              => trans('validations/api/guest/auth/register.username.max'),
            'username.unique'           => trans('validations/api/guest/auth/register.username.unique'),
            'email.required'            => trans('validations/api/guest/auth/register.email.required'),
            'email.regex'               => trans('validations/api/guest/auth/register.email.regex'),
            'gender_id.required'        => trans('validations/api/guest/auth/register.gender_id.required'),
            'gender_id.integer'         => trans('validations/api/guest/auth/register.gender_id.integer'),
            'birth_date.required'       => trans('validations/api/guest/auth/register.birth_date.required'),
            'birth_date.date_format'    => trans('validations/api/guest/auth/register.birth_date.date_format'),
            'password.required'         => trans('validations/api/guest/auth/register.password.required'),
            'password.string'           => trans('validations/api/guest/auth/register.password.string'),
            'password_confirm.required' => trans('validations/api/guest/auth/register.password_confirm.required'),
            'password_confirm.string'   => trans('validations/api/guest/auth/register.password_confirm.string'),
            'hide_gender.boolean'       => trans('validations/api/guest/auth/register.hide_gender.boolean'),
            'hide_age.boolean'          => trans('validations/api/guest/auth/register.hide_age.boolean'),
            'country_place_id.required' => trans('validations/api/guest/auth/register.country_place_id.required'),
            'country_place_id.string'   => trans('validations/api/guest/auth/register.country_place_id.string'),
            'country_place_id.exists'   => trans('validations/api/guest/auth/register.country_place_id.exists'),
            'region_place_id.string'    => trans('validations/api/guest/auth/register.region_place_id.string'),
            'region_place_id.exists'    => trans('validations/api/guest/auth/register.region_place_id.exists'),
            'invitation_code.string'    => trans('validations/api/guest/auth/register.invitation_code.string')
        ];
    }
}
