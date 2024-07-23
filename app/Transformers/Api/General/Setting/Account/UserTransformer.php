<?php

namespace App\Transformers\Api\General\Setting\Account;

use App\Models\MySql\User\User;
use App\Services\User\UserService;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UserTransformer constructor
     */
    public function __construct()
    {
        /** @var UserService userService */
        $this->userService = new UserService();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'email'                      => $user->email,
            'fast_order'                 => $this->userService->getFastOrderPageUrl($user),
            'fast_order_enabled'         => $user->enable_fast_order,
            'email_verified'             => (bool)$user->email_verified_at,
            'email_verified_at'          => $user->email_verified_at ?
                Carbon::parse($user->email_verified_at)->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'email_too_many_attempts'    => $user->next_email_attempt_at && Carbon::now() < $user->next_email_attempt_at,
            'password_too_many_attempts' => $user->next_password_attempt_at && Carbon::now() < $user->next_password_attempt_at,
            'streaming_platforms'        => [],
            'socials'                    => []
        ];
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
