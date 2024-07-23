<?php

namespace App\Transformers\Api\General\Cart;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserBalance;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Services\User\UserService;
use App\Transformers\Api\General\Cart\Balance\UserBalanceTransformer;
use App\Transformers\Api\General\Cart\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\General\Cart
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybes;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * FormTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection $vybes
     * @param EloquentCollection|null $activityImages
     * @param EloquentCollection|null $userAvatars
     */
    public function __construct(
        User $user,
        EloquentCollection $vybes,
        ?EloquentCollection $activityImages = null,
        ?EloquentCollection $userAvatars = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection vybes */
        $this->vybes = $vybes;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var  vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'balance',
        'vybes'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'tax_rate' => $this->userService->getTotalTaxPercent(
                $this->authUser
            )
        ];
    }

    /**
     * @return Item|null
     */
    public function includeBalance() : ?Item
    {
        $buyerBalance = null;

        if ($this->authUser->relationLoaded('balances')) {

            /** @var UserBalance $balance */
            foreach ($this->authUser->balances as $balance) {
                if ($balance->getType()->isBuyer()) {
                    $buyerBalance = $balance;
                }
            }
        }

        return $buyerBalance ? $this->item($buyerBalance, new UserBalanceTransformer) : null;
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function includeVybes() : Collection
    {
        return $this->collection(
            $this->vybes,
            new VybeTransformer(
                $this->vybeImageRepository->getByVybes(
                    $this->vybes
                ),
                $this->vybeVideoRepository->getByVybes(
                    $this->vybes
                ),
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
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
