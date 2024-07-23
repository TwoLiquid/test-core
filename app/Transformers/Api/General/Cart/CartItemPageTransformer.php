<?php

namespace App\Transformers\Api\General\Cart;

use App\Models\MySql\CartItem;
use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class CartItemPageTransformer
 *
 * @package App\Transformers\Api\General\Cart
 */
class CartItemPageTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $cartItems;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $timeslots;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var bool|null
     */
    protected ?bool $withForm;

    /**
     * CartItemPageTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection $cartItems
     * @param EloquentCollection $timeslots
     * @param EloquentCollection|null $activityImages
     * @param EloquentCollection|null $userAvatars
     * @param bool|null $withForm
     */
    public function __construct(
        User $user,
        EloquentCollection $cartItems,
        EloquentCollection $timeslots,
        ?EloquentCollection $activityImages = null,
        ?EloquentCollection $userAvatars = null,
        ?bool $withForm = false
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection cartItems */
        $this->cartItems = $cartItems;

        /** @var EloquentCollection timeslots */
        $this->timeslots = $timeslots;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var bool withForm */
        $this->withForm = $withForm;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'cart_items'
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
        if ($this->withForm === true) {
            $vybes = new EloquentCollection();

            /** @var CartItem $cartItem */
            foreach ($this->cartItems as $cartItem) {
                if ($cartItem->relationLoaded('appearanceCase')) {
                    $appearanceCase = $cartItem->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        $vybes->push(
                            $appearanceCase->vybe
                        );
                    }
                }
            }

            return $this->item([],
                new FormTransformer(
                    $this->authUser,
                    $vybes->unique(),
                    $this->activityImages,
                    $this->userAvatars
                )
            );
        }

        return null;
    }

    /**
     * @return Collection
     */
    public function includeCartItems() : Collection
    {
        return $this->collection(
            $this->cartItems,
            new CartItemTransformer(
                $this->authUser,
                $this->timeslots,
                $this->activityImages,
                $this->userAvatars
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'cart_item_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'cart_item_page';
    }
}
