<?php

namespace App\Transformers\Api\General\Cart;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\CartItem;
use App\Models\MySql\User\User;
use App\Services\Timeslot\TimeslotService;
use App\Transformers\Api\General\Cart\Timeslot\TimeslotTransformer;
use App\Transformers\Api\General\Cart\Vybe\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class CartItemTransformer
 *
 * @package App\Transformers\Api\General\Cart
 */
class CartItemTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

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
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * VybeTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $timeslots
     * @param EloquentCollection|null $activityImages
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        User $user,
        ?EloquentCollection $timeslots = null,
        ?EloquentCollection $activityImages = null,
        ?EloquentCollection $userAvatars = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection timeslots */
        $this->timeslots = $timeslots;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearance_case',
        'timeslot'
    ];

    /**
     * @param CartItem $cartItem
     *
     * @return array
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function transform(CartItem $cartItem) : array
    {
        return [
            'id'            => $cartItem->id,
            'vybe_id'       => $cartItem->appearanceCase->vybe_id,
            'datetime_from' => Carbon::parse($cartItem->datetime_from)
                ->addSeconds(
                    $this->authUser->timezone
                        ->getCurrentOffset()
                        ->offset
                )
                ->format('Y-m-d\TH:i:s.v\Z'),
            'datetime_to'   => Carbon::parse($cartItem->datetime_to)
                ->addSeconds(
                    $this->authUser->timezone
                        ->getCurrentOffset()
                        ->offset
                )
                ->format('Y-m-d\TH:i:s.v\Z'),
            'quantity'      => $cartItem->quantity,
            'available'     => $this->timeslotService->isAvailable(
                $cartItem->user,
                $cartItem->appearanceCase,
                $cartItem->datetime_from,
                $cartItem->datetime_to,
                false
            )
        ];
    }

    /**
     * @param CartItem $cartItem
     *
     * @return Item|null
     */
    public function includeAppearanceCase(CartItem $cartItem) : ?Item
    {
        $appearanceCase = null;

        if ($cartItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $cartItem->appearanceCase;
        }

        return $appearanceCase ? $this->item($appearanceCase, new AppearanceCaseTransformer) : null;
    }

    /**
     * @param CartItem $cartItem
     *
     * @return Item|null
     */
    public function includeTimeslot(CartItem $cartItem) : ?Item
    {
        $timeslot = $this->timeslots?->filter(function ($item) use ($cartItem) {
            return $item->datetime_from == $cartItem->datetime_from &&
                $item->datetime_to == $cartItem->datetime_to;
        })->first();

        return $timeslot ? $this->item($timeslot, new TimeslotTransformer($this->authUser, $this->userAvatars)) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'cart_item';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'cart_items';
    }
}
