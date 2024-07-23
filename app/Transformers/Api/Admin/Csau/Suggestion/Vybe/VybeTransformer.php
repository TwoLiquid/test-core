<?php

namespace App\Transformers\Api\Admin\Csau\Suggestion\Vybe;

use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\Admin\Csau\Suggestion\User\UserTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Suggestion\Vybe
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'        => $vybe->id,
            'title'     => $vybe->title,
            'version'   => $vybe->version,
            'period_id' => $vybe->period_id,
            'user_count'=> $vybe->user_count
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeUser(Vybe $vybe) : ?Item
    {
        $user = null;

        if ($vybe->relationLoaded('user')) {
            $user = $vybe->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
