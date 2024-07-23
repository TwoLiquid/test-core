<?php

namespace App\Transformers\Api\Admin\Vybe\UnpublishRequest;

use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class ToastMessageTypeTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\UnpublishRequest
 */
class ToastMessageTypeTransformer extends BaseTransformer
{
    /**
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return array
     */
    public function transform(ToastMessageTypeListItem $toastMessageTypeListItem) : array
    {
        return [
            'id'   => $toastMessageTypeListItem->id,
            'code' => $toastMessageTypeListItem->code,
            'name' => $toastMessageTypeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'toast_message_type';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'toast_message_types';
    }
}
