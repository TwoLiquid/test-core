<?php

namespace App\Microservices\Chat\Transformers;

use App\Microservices\Chat\Responses\ChatResponse;
use App\Transformers\BaseTransformer;

/**
 * Class OffensiveWordTransformer
 *
 * @package App\Microservices\Chat\Transformers
 */
class ChatTransformer extends BaseTransformer
{
    /**
     * @param ChatResponse $chatResponse
     *
     * @return array
     */
    public function transform(ChatResponse $chatResponse) : array
    {
        return [
            'id' => $chatResponse->id
        ];
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
