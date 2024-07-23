<?php

namespace App\Lists\Request\Type;

/**
 * Class RequestTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Request\Type
 */
class RequestTypeListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var int|null
     */
    public ?int $count;

    /**
     * RequestTypeListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param int|null $count
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        ?int $count = null
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var int count */
        $this->count = $count;
    }

    /**
     * @return bool
     */
    public function isUserProfileRequest() : bool
    {
        return $this->code == 'user_profile_request';
    }

    /**
     * @return bool
     */
    public function isUserIdVerificationRequest() : bool
    {
        return $this->code == 'user_id_verification_request';
    }

    /**
     * @return bool
     */
    public function isUserDeactivationRequest() : bool
    {
        return $this->code == 'user_deactivation_request';
    }

    /**
     * @return bool
     */
    public function isUserUnsuspendRequest() : bool
    {
        return $this->code == 'user_unsuspend_request';
    }

    /**
     * @return bool
     */
    public function isUserDeletionRequest() : bool
    {
        return $this->code == 'user_deletion_request';
    }

    /**
     * @return bool
     */
    public function isVybePublishRequest() : bool
    {
        return $this->code == 'vybe_publish_request';
    }

    /**
     * @return bool
     */
    public function isVybeChangeRequest() : bool
    {
        return $this->code == 'vybe_change_request';
    }

    /**
     * @return bool
     */
    public function isVybeUnpublishRequest() : bool
    {
        return $this->code == 'vybe_unpublish_request';
    }

    /**
     * @return bool
     */
    public function isVybeUnsuspendRequest() : bool
    {
        return $this->code == 'vybe_unsuspend_request';
    }

    /**
     * @return bool
     */
    public function isVybeDeletionRequest() : bool
    {
        return $this->code == 'vybe_deletion_request';
    }

    /**
     * @return bool
     */
    public function isBillingChangeRequest() : bool
    {
        return $this->code == 'billing_change_request';
    }

    /**
     * @return bool
     */
    public function isWithdrawalRequest() : bool
    {
        return $this->code == 'withdrawal_request';
    }

    /**
     * @return bool
     */
    public function isPayoutMethodRequest() : bool
    {
        return $this->code == 'payout_method_request';
    }

    /**
     * @return int|null
     */
    public function getCount() : ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     *
     * @return void
     */
    public function setCount(
        ?int $count
    ) : void
    {
        $this->count = $count ?: 0;
    }
}