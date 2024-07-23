<?php

namespace App\Transformers\Api\General\Dashboard\Wallet\AddFunds;

use App\Exceptions\DatabaseException;
use App\Settings\User\AddFundsSetting;
use App\Transformers\BaseTransformer;

/**
 * Class SettingsTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet\AddFunds
 */
class SettingsTransformer extends BaseTransformer
{
    /**
     * @var AddFundsSetting
     */
    protected AddFundsSetting $addFundsSetting;

    /**
     * SettingsTransformer constructor
     */
    public function __construct()
    {
        /** @var AddFundsSetting addFundsSetting */
        $this->addFundsSetting = new AddFundsSetting();
    }

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform() : array
    {
        return [
            'minimum_amount' => $this->addFundsSetting->getMinimumAmount(),
            'maximum_amount' => $this->addFundsSetting->getMaximumAmount()
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'settings';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'settings';
    }
}
