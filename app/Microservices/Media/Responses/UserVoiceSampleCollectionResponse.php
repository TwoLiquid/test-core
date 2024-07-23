<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class VoiceSampleCollectionResponse
 *
 * @property Collection $voiceSamples
 *
 * @package App\Microservices\Media\Responses
 */
class UserVoiceSampleCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $voiceSamples;

    /**
     * UserVoiceSampleCollectionResponse constructor
     *
     * @param array $voiceSamples
     * @param string|null $message
     */
    public function __construct(
        array $voiceSamples,
        ?string $message
    )
    {
        $this->voiceSamples = new Collection();

        /** @var object $voiceSample */
        foreach ($voiceSamples as $voiceSample) {
            $this->voiceSamples->push(
                new UserVoiceSampleResponse(
                    $voiceSample,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}