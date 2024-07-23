<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserIdVerificationImageCollectionResponse
 *
 * @property Collection $userIdVerificationImages
 *
 * @package App\Microservices\Media\Responses
 */
class UserIdVerificationImageCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $userIdVerificationImages;

    /**
     * UserIdVerificationImageCollectionResponse constructor
     *
     * @param array $userIdVerificationImages
     * @param string|null $message
     */
    public function __construct(
        array $userIdVerificationImages,
        ?string $message
    )
    {
        $this->userIdVerificationImages = new Collection();

        /** @var object $userIdVerificationImage */
        foreach ($userIdVerificationImages as $userIdVerificationImage) {
            $this->userIdVerificationImages->push(
                new UserIdVerificationImageResponse(
                    $userIdVerificationImage,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}