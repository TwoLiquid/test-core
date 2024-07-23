<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\AlertSoundResponse;
use App\Transformers\BaseTransformer;

/**
 * Class AlertSoundTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class AlertSoundTransformer extends BaseTransformer
{
    /**
     * @param AlertSoundResponse $alertSoundResponse
     *
     * @return array
     */
    public function transform(AlertSoundResponse $alertSoundResponse) : array
    {
        return [
            'id'       => $alertSoundResponse->id,
            'alert_id' => $alertSoundResponse->alertId,
            'url'      => $alertSoundResponse->url,
            'duration' => $alertSoundResponse->duration,
            'mime'     => $alertSoundResponse->mime,
            'active'   => $alertSoundResponse->active
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_sound';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_sounds';
    }
}
