<?php

namespace App\Http\Controllers\Api\Admin\General\Setting;

use App\Http\Controllers\Api\Admin\General\Setting\Interfaces\GeneralSettingControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Setting\General\UpdateRequest;
use App\Settings\Aggregator\GeneralSettingAggregator;
use Illuminate\Http\JsonResponse;

/**
 * Class GeneralSettingController
 *
 * @package App\Http\Controllers\Api\Admin\General\Setting
 */
final class GeneralSettingController extends BaseController implements GeneralSettingControllerInterface
{
    /**
     * @var GeneralSettingAggregator
     */
    protected GeneralSettingAggregator $generalSettingAggregator;

    /**
     * GeneralSettingController constructor
     */
    public function __construct()
    {
        /** @var GeneralSettingAggregator generalSettingAggregator */
        $this->generalSettingAggregator = new GeneralSettingAggregator();
    }

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return $this->respondWithSuccess([
            'settings' => $this->generalSettingAggregator->getAdminMain()
        ], trans('validations/api/admin/general/setting/general/index.result.success'));
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
        $this->generalSettingAggregator->saveAdminMain(
            $request->input('settings')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/setting/general/update.result.success')
        );
    }
}
