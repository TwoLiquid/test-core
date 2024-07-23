<?php

namespace App\Http\Controllers\Api\Admin\General\Setting;

use App\Http\Controllers\Api\Admin\General\Setting\Interfaces\VybeSettingControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Setting\Vybe\UpdateRequest;
use App\Settings\Aggregator\VybeSettingAggregator;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeSettingController
 *
 * @package App\Http\Controllers\Api\Admin\General
 */
final class VybeSettingController extends BaseController implements VybeSettingControllerInterface
{
    /**
     * @var VybeSettingAggregator
     */
    protected VybeSettingAggregator $vybeSettingAggregator;

    /**
     * VybeSettingController constructor
     */
    public function __construct()
    {
        /** @var VybeSettingAggregator vybeSettingAggregator */
        $this->vybeSettingAggregator = new VybeSettingAggregator();
    }

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return $this->respondWithSuccess([
            'settings' => $this->vybeSettingAggregator->getAdminMain()
        ], trans('validations/api/admin/general/setting/vybe/index.result.success'));
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
        $this->vybeSettingAggregator->saveAdminMain(
            $request->input('settings')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/setting/vybe/update.result.success')
        );
    }
}
