<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserAvatarCollectionResponse
 *
 * @property Collection $avatars
 *
 * @package App\Microservices\Media\Responses
 */
class UserAvatarCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $avatars;

    /**
     * UserAvatarCollectionResponse constructor
     *
     * @param array $avatars
     * @param string|null $message
     */
    public function __construct(
        array $avatars,
        ?string $message
    )
    {
        $this->avatars = new Collection();

        /** @var object $avatar */
        foreach ($avatars as $avatar) {
            $this->avatars->push(
                new UserAvatarResponse(
                    $avatar,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}