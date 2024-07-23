<?php

namespace App\Transformers\Api\General\Setting\IdVerification;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\User\User;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationTransformer
 *
 * @package App\Transformers\Api\General\Setting\IdVerification
 */
class UserIdVerificationTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userIdVerificationImages;

    /**
     * @var UserIdVerificationRequest|null
     */
    protected ?UserIdVerificationRequest $userIdVerificationRequest;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * UserIdVerificationTransformer constructor
     *
     * @param User $user
     * @param UserIdVerificationRequest|null $userIdVerificationRequest
     * @param EloquentCollection|null $userIdVerificationImages
     */
    public function __construct(
        User $user,
        ?UserIdVerificationRequest $userIdVerificationRequest,
        EloquentCollection $userIdVerificationImages = null
    )
    {
        /** @var User user */
        $this->user = $user;

        /** @var UserIdVerificationRequest userIdVerificationRequest */
        $this->userIdVerificationRequest = $userIdVerificationRequest;

        /** @var EloquentCollection userIdVerificationImages */
        $this->userIdVerificationImages = $userIdVerificationImages;

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'user_id_verification_request'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeUser() : ?Item
    {
        $user = $this->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserIdVerificationRequest() : ?Item
    {
        $userIdVerificationRequest = null;

        if ($this->userIdVerificationRequest) {
            if ($this->userIdVerificationRequest->shown === false) {
                if ($this->userIdVerificationRequest->getRequestStatus()->isAccepted() ||
                    $this->userIdVerificationRequest->getRequestStatus()->isCanceled()
                ) {
                    $this->userIdVerificationRequestRepository->updateShown(
                        $this->userIdVerificationRequest,
                        true
                    );
                }

                $userIdVerificationRequest = $this->userIdVerificationRequest;
            }
        }

        return $userIdVerificationRequest ?
            $this->item(
                $userIdVerificationRequest,
                new UserIdVerificationRequestTransformer(
                    $this->userIdVerificationImages
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verifications';
    }
}
