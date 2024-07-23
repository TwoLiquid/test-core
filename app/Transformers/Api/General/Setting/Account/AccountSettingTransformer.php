<?php

namespace App\Transformers\Api\General\Setting\Account;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class AccountSettingTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account
 */
class AccountSettingTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * AccountSettingTransformer constructor
     *
     * @param User $user
     */
    public function __construct(
        User $user
    )
    {
        /** @var User user */
        $this->user = $user;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'requests',
        'user'
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
    public function includeForm() : ?Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeRequests() : ?Item
    {
        return $this->item([], new RequestTransformer($this->user));
    }

    /**
     * @return Item|null
     */
    public function includeUser() : ?Item
    {
        return $this->item($this->user, new UserTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'account_setting';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'account_settings';
    }
}
