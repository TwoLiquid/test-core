<?php

namespace App\Services\User\Interfaces;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserIdVerificationRequestServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserIdVerificationRequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection|null $userIdVerificationRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $userIdVerificationRequests
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     */
    public function getLastForUser(
        User $user
    ) : ?UserIdVerificationRequest;
}