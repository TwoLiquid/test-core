<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertSoundCollectionResponse
 *
 * @property Collection $sounds
 *
 * @package App\Microservices\Media\Responses
 */
class AlertSoundCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $sounds;

    /**
     * AlertSoundCollectionResponse constructor
     *
     * @param array $sounds
     * @param string|null $message
     */
    public function __construct(
        array $sounds,
        ?string $message
    )
    {
        $this->sounds = new Collection();

        /** @var object $sound */
        foreach ($sounds as $sound) {
            $this->sounds->push(
                new AlertSoundResponse(
                    $sound,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}