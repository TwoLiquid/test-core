<?php

namespace App\Transformers\Api\General\Vybe\VybeRatingVote;

use App\Models\MySql\Vybe\VybeRatingVote;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeRatingVoteTransformer
 *
 * @package App\Transformers\Api\General\Vybe\VybeRatingVote
 */
class VybeRatingVoteTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe',
        'user'
    ];

    /**
     * @param VybeRatingVote $vybeRatingVote
     *
     * @return array
     */
    public function transform(VybeRatingVote $vybeRatingVote) : array
    {
        return [
            'id'     => $vybeRatingVote->id,
            'rating' => $vybeRatingVote->rating
        ];
    }

    /**
     * @param VybeRatingVote $vybeRatingVote
     *
     * @return Item|null
     */
    public function includeVybe(VybeRatingVote $vybeRatingVote) : ?Item
    {
        $vybe = null;

        if ($vybeRatingVote->relationLoaded('vybe')) {
            $vybe = $vybeRatingVote->vybe;
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param VybeRatingVote $vybeRatingVote
     *
     * @return Item|null
     */
    public function includeUser(VybeRatingVote $vybeRatingVote) : ?Item
    {
        $user = null;

        if ($vybeRatingVote->relationLoaded('user')) {
            $user = $vybeRatingVote->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_rating_vote';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_rating_votes';
    }
}
