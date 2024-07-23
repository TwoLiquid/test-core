<?php

namespace App\Transformers\Api\Admin\User\IdVerification;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\User\IdVerification
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_id_verification_status',
        'account_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'                     => $user->id,
            'auth_id'                => $user->auth_id,
            'username'               => $user->username,
            'referred_by'            => null,
            'suspend_admin'          => null,
            'verification_suspended' => $user->verification_suspended,
            'suspend_reason'         => $user->suspend_reason,
            'vpn_user'               => null,
            'ip_and_duplicates'      => null,
            'signed_up_at'           => $user->signed_up_at ?
                $user->signed_up_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeUserIdVerificationStatus(User $user) : ?Item
    {
        $userIdVerificationStatus = $user->getIdVerificationStatus();

        return $userIdVerificationStatus ? $this->item($userIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeAccountStatus(User $user) : ?Item
    {
        $accountStatus = $user->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'users';
    }
}
