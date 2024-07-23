<?php

namespace App\Transformers\Api\General\Setting\Payout\Method;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class PayoutMethodListTransformer
 *
 * @package App\Transformers\Api\General\Setting\Payout\Method
 */
class PayoutMethodListTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $payoutMethods;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userPayoutMethods;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PayoutMethodPageTransformer constructor
     *
     * @param EloquentCollection $payoutMethods
     * @param EloquentCollection $userPayoutMethods
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        EloquentCollection $payoutMethods,
        EloquentCollection $userPayoutMethods,
        EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var EloquentCollection payoutMethods */
        $this->payoutMethods = $payoutMethods;

        /** @var EloquentCollection userPayoutMethods */
        $this->userPayoutMethods = $userPayoutMethods;

        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payout_methods',
        'user_payout_methods'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includePayoutMethods() : ?Collection
    {
        return $this->collection(
            $this->payoutMethods,
            new PaymentMethodTransformer(
                $this->paymentMethodImages
            )
        );
    }

    /**
     * @return Collection|null
     */
    public function includeUserPayoutMethods() : ?Collection
    {
        return $this->collection(
            $this->userPayoutMethods,
            new PaymentMethodTransformer(
                $this->paymentMethodImages
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_lists';
    }
}
