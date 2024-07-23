<?php

namespace App\Http\Controllers\Api\Admin\General\Setting;

use App\Http\Controllers\Api\Admin\General\Setting\Interfaces\RequestSettingControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Setting\General\UpdateRequest;
use App\Settings\Aggregator\RequestSettingAggregator;
use Illuminate\Http\JsonResponse;

/**
 * Class RequestSettingController
 * 
 * @package App\Http\Controllers\Api\Admin\General\Setting
 */
final class RequestSettingController extends BaseController implements RequestSettingControllerInterface
{
    /**
     * @var RequestSettingAggregator
     */
    protected RequestSettingAggregator $requestSettingAggregator;

    /**
     * RequestSettingController constructor
     */
    public function __construct()
    {
        /** @var RequestSettingAggregator requestSettingAggregator */
        $this->requestSettingAggregator = new RequestSettingAggregator();
    }

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return $this->respondWithSuccess([
            'settings' => $this->requestSettingAggregator->getAdminMain()
        ], trans('validations/api/admin/general/setting/request/index.result.success'));
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
        $this->requestSettingAggregator->saveAdminMain(
            $request->input('settings')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/setting/request/update.result.success')
        );
    }
}
