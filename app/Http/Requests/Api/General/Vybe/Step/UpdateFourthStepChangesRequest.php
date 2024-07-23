<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateFourthStepChangesRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class UpdateFourthStepChangesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 4-th step
            'deleted_images_ids'   => 'array|nullable',
            'deleted_images_ids.*' => 'required|integer',
            'deleted_videos_ids'   => 'array|nullable',
            'deleted_videos_ids.*' => 'required|integer',
            'files'                => 'array|nullable',
            'files.*.content'      => 'required|string',
            'files.*.extension'    => 'required|string',
            'files.*.mime'         => 'required|string',
            'files.*.main'         => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 4-th step
            'deleted_images_ids.array'      => trans('validations/api/general/vybe/step/updateFourthStepChanges.deleted_images_ids.array'),
            'deleted_images_ids.*.required' => trans('validations/api/general/vybe/step/updateFourthStepChanges.deleted_images_ids.*.required'),
            'deleted_images_ids.*.integer'  => trans('validations/api/general/vybe/step/updateFourthStepChanges.deleted_images_ids.*.integer'),
            'deleted_videos_ids.array'      => trans('validations/api/general/vybe/step/updateFourthStepChanges.deleted_videos_ids.array'),
            'deleted_videos_ids.*.required' => trans('validations/api/general/vybe/step/updateFourthStepChanges.deleted_videos_ids.*.required'),
            'deleted_videos_ids.*.integer'  => trans('validations/api/general/vybe/step/updateFourthStepChanges.deleted_videos_ids.*.integer'),
            'files.array'                   => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.array'),
            'files.*.content.required'      => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.content.required'),
            'files.*.content.string'        => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.content.string'),
            'files.*.mime.required'         => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.mime.required'),
            'files.*.mime.string'           => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.mime.string'),
            'files.*.extension.required'    => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.extension.required'),
            'files.*.extension.string'      => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.extension.string'),
            'files.*.main.boolean'          => trans('validations/api/general/vybe/step/updateFourthStepChanges.files.*.main.boolean')
        ];
    }
}
