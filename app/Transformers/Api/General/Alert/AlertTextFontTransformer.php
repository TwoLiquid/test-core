<?php

namespace App\Transformers\Api\General\Alert;

use App\Lists\Alert\Text\Font\AlertTextFontListItem;
use App\Transformers\BaseTransformer;

/**
 * Class AlertTextFontTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertTextFontTransformer extends BaseTransformer
{
    /**
     * @param AlertTextFontListItem $alertTextFontListItem
     *
     * @return array
     */
    public function transform(AlertTextFontListItem $alertTextFontListItem) : array
    {
        return [
            'id'   => $alertTextFontListItem->id,
            'code' => $alertTextFontListItem->code,
            'name' => $alertTextFontListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_text_font';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_text_fonts';
    }
}
