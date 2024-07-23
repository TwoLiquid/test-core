<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class AdminAvatarCollectionResponse
 *
 * @property Collection $avatars
 *
 * @package App\Microservices\Media\Responses
 */
class AdminAvatarCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $avatars;

    /**
     * AdminAvatarCollectionResponse constructor
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
                new AdminAvatarResponse(
                    $avatar,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}