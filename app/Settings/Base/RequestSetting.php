<?php

namespace App\Settings\Base;

use App\Repositories\Request\RequestSettingRepository;
use App\Settings\Base\Interfaces\RequestSettingInterface;

/**
 * Class RequestSetting
 *
 * @package App\Settings\Base
 */
abstract class RequestSetting extends BaseSetting implements RequestSettingInterface
{
    /**
     * @var RequestSettingRepository
     */
    protected RequestSettingRepository $requestSettingRepository;

    /**
     * RequestSetting constructor
     */
    public function __construct()
    {
        /** @var RequestSettingRepository requestSettingRepository */
        $this->requestSettingRepository = new RequestSettingRepository();
    }
}