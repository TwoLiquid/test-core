<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class ChatSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class ChatSectionTransformer extends BaseTransformer
{
    /**
     * @param array $chats
     *
     * @return array
     */
    public function transform(array $chats) : array
    {
        return $chats;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'chat';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'chats';
    }
}
