<?php

namespace App\Settings\Base;

use App\Repositories\General\GeneralSettingRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Settings\Base\Interfaces\GeneralSettingInterface;

/**
 * Class GeneralSetting
 *
 * @package App\Settings\Base
 */
abstract class GeneralSetting extends BaseSetting implements GeneralSettingInterface
{
    /**
     * @var GeneralSettingRepository
     */
    protected GeneralSettingRepository $generalSettingRepository;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * GeneralSetting constructor
     */
    public function __construct()
    {
        /** @var GeneralSettingRepository generalSettingRepository */
        $this->generalSettingRepository = new GeneralSettingRepository();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();
    }
}