<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Models\MySql\User\User;
use App\Transformers\Api\Guest\User\User\Language\LanguageTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class UserRecentVisitTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class UserRecentVisitTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'languages'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'       => $user->id,
            'auth_id'  => $user->auth_id,
            'username' => $user->username
        ];
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeLanguages(User $user) : ?Collection
    {
        $languages = null;

        if ($user->relationLoaded('languages')) {
            $languages = $user->languages;
        }

        return $languages ? $this->collection($languages, new LanguageTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_recent_visit';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_recent_visits';
    }
}
