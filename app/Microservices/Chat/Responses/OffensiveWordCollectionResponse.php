<?php

namespace App\Microservices\Chat\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class OffensiveWordCollectionResponse
 *
 * @property Collection $words
 *
 * @package App\Microservices\Chat\Responses
 */
class OffensiveWordCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $words;

    /**
     * OffensiveWordCollectionResponse constructor
     *
     * @param array $words
     * @param string|null $message
     */
    public function __construct(
        array $words,
        ?string $message
    )
    {
        $this->words = new Collection();

        /** @var object $word */
        foreach ($words as $word) {
            $this->words->push(
                new OffensiveWordResponse(
                    $word,
                    $message
                )
            );
        }

        parent::__construct($message);
    }
}