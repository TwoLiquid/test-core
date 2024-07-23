<?php

namespace App\Transformers\Api\Guest\Setting\Type;

use App\Lists\Setting\Type\SettingTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class SettingTypeTransformer
 *
 * @package App\Transformers\Api\Guest\Setting\Type
 */
class SettingTypeTransformer extends BaseTransformer
{
    /**
     * @param SettingTypeListItem $settingTypeListItem
     *
     * @return array
     */
    public function transform(SettingTypeListItem $settingTypeListItem) : array
    {
        return [
            'id'   => $settingTypeListItem->id,
            'code' => $settingTypeListItem->code,
            'name' => $settingTypeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'setting_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'setting_types';
    }
}
