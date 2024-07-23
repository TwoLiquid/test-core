<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip\Pdf;

use App\Models\MySql\User\User;
use App\Transformers\Api\Admin\Invoice\Tip\Buyer\Pdf\BillingTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip\Pdf
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'billing'
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
     * @return Item|null]
     */
    public function includeBilling(User $user) : ?Item
    {
        $billing = null;

        if ($user->relationLoaded('billing')) {
            $billing = $user->billing;
        }

        return $billing ? $this->item($billing, new BillingTransformer) : null;
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
