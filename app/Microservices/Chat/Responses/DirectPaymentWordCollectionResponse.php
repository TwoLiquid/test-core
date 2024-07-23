<?php

namespace App\Microservices\Chat\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class DirectPaymentWordCollectionResponse
 *
 * @property Collection $words
 *
 * @package App\Microservices\Chat\Responses
 */
class DirectPaymentWordCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $words;

    /**
     * DirectPaymentWordCollectionResponse constructor
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
                new DirectPaymentWordResponse(
                    $word,
                    $message
                )
            );
        }

        parent::__construct($message);
    }
}