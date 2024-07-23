<?php

namespace App\Http\Controllers\Api\Admin\General\Setting;

use App\Http\Controllers\Api\Admin\General\Setting\Interfaces\UserSettingControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Setting\User\IndexRequest;
use App\Http\Requests\Api\Admin\General\Setting\User\UpdateRequest;
use App\Settings\Aggregator\UserSettingAggregator;
use Illuminate\Http\JsonResponse;

/**
 * Class UserSettingController
 *
 * @package App\Http\Controllers\Api\Admin\General
 */
final class UserSettingController extends BaseController implements UserSettingControllerInterface
{
    /**
     * @var UserSettingAggregator
     */
    protected UserSettingAggregator $userSettingAggregator;

    /**
     * UserSettingController constructor
     */
    public function __construct()
    {
        /** @var UserSettingAggregator userSettingAggregator */
        $this->userSettingAggregator = new UserSettingAggregator();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        if ($request->input('category') == 'buyer') {
            return $this->respondWithSuccess([
                'settings' => $this->userSettingAggregator->getBuyer()
            ], trans('validations/api/admin/general/setting/user/index.result.success'));
        }

        return $this->respondWithSuccess([
            'settings' => $this->userSettingAggregator->getSeller()
        ], trans('validations/api/admin/general/setting/user/index.result.success'));
    }

    /**
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse
    {
        if ($request->input('category') == 'buyer') {
            $this->userSettingAggregator->saveBuyer(
                $request->input('settings')
            );
        } else {
            $this->userSettingAggregator->saveSeller(
                $request->input('settings')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/setting/user/update.result.success')
        );
    }
}
