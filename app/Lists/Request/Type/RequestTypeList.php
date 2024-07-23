<?php

namespace App\Lists\Request\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequestTypeList
 *
 * @package App\Lists\Request\Type
 */
class RequestTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'request/type';

    /**
     * Request type list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'user_profile_request'
        ],
        [
            'id'   => 2,
            'code' => 'user_id_verification_request'
        ],
        [
            'id'   => 3,
            'code' => 'user_deactivation_request'
        ],
        [
            'id'   => 4,
            'code' => 'user_unsuspend_request'
        ],
        [
            'id'   => 5,
            'code' => 'user_deletion_request'
        ],
        [
            'id'   => 6,
            'code' => 'vybe_publish_request'
        ],
        [
            'id'   => 7,
            'code' => 'vybe_change_request'
        ],
        [
            'id'   => 8,
            'code' => 'vybe_unpublish_request'
        ],
        [
            'id'   => 9,
            'code' => 'vybe_unsuspend_request'
        ],
        [
            'id'   => 10,
            'code' => 'vybe_deletion_request'
        ],
        [
            'id'   => 11,
            'code' => 'billing_change_request'
        ],
        [
            'id'   => 12,
            'code' => 'withdrawal_request'
        ],
        [
            'id'   => 13,
            'code' => 'payout_method_request'
        ]
    ];

    /**
     * User group requests
     */
    protected const USER_GROUP  = [
        'user_profile_request',
        'user_id_verification_request',
        'user_deactivation_request',
        'user_unsuspend_request',
        'user_deletion_request'
    ];

    /**
     * Vybe group requests
     */
    protected const VYBE_GROUP  = [
        'vybe_publish_request',
        'vybe_change_request',
        'vybe_unpublish_request',
        'vybe_unsuspend_request',
        'vybe_deletion_request'
    ];

    /**
     * Finance group requests
     */
    protected const FINANCE_GROUP  = [
        'billing_change_request',
        'withdrawal_request',
        'payout_method_request'
    ];

    /**
     * List of fields requiring translation
     */
    protected const TRANSLATES = [
        'name'
    ];

    /**
     * @return Collection
     */
    public static function getItems() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return RequestTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?RequestTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new RequestTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return Collection
     */
    public static function getUserGroup() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['code'], self::USER_GROUP)) {
                $items->push(
                    new RequestTypeListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getVybeGroup() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['code'], self::VYBE_GROUP)) {
                $items->push(
                    new RequestTypeListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getFinanceGroup() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['code'], self::FINANCE_GROUP)) {
                $items->push(
                    new RequestTypeListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getUserProfileRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'user_profile_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getUserIdVerificationRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'user_id_verification_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getUserDeactivationRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'user_deactivation_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getUserUnsuspendRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'user_unsuspend_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getUserDeletionRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'user_deletion_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getVybePublishRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vybe_publish_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getVybeChangeRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vybe_change_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getVybeUnpublishRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vybe_unpublish_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getVybeUnsuspendRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vybe_unsuspend_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getVybeDeletionRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vybe_deletion_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getBillingChangeRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'billing_change_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getWithdrawalRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'withdrawal_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestTypeListItem|null
     */
    public static function getPayoutMethodRequest() : ?RequestTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'payout_method_request') {
                return new RequestTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}