<?php

namespace App\Settings\Base;

use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeSettingRepository;
use App\Settings\Base\Interfaces\VybeSettingInterface;

/**
 * Class VybeSetting
 *
 * @package App\Settings\Base
 */
abstract class VybeSetting extends BaseSetting implements VybeSettingInterface
{
    /**
     * @var Vybe|null 
     */
    protected ?Vybe $vybe = null;

    /**
     * @var VybeSettingRepository
     */
    protected VybeSettingRepository $vybeSettingRepository;

    /**
     * VybeSetting constructor
     */
    public function __construct()
    {
        /** @var VybeSettingRepository vybeSettingRepository */
        $this->vybeSettingRepository = new VybeSettingRepository();
    }

    /**
     * @return Vybe|null
     */
    public function getVybe() : ?Vybe
    {
        return $this->vybe;
    }

    /**
     * @param Vybe $vybe
     */
    public function setVybe(
        Vybe $vybe
    ) : void
    {
        $this->vybe = $vybe;
    }
}