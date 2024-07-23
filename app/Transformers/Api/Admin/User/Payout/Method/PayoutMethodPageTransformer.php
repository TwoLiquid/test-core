<?php

namespace App\Transformers\Api\Admin\User\Payout\Method;

use App\Models\MySql\Payment\PaymentMethod;
use App\Transformers\Api\General\Setting\Payout\Method\Request\PayoutMethodRequestTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PayoutMethodPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Payout\Method
 */
class PayoutMethodPageTransformer extends BaseTransformer
{
    /**
     * @var PaymentMethod
     */
    protected PaymentMethod $paymentMethod;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userPayoutMethods;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $payoutMethodRequests;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PayoutMethodPageTransformer constructor
     *
     * @param PaymentMethod $paymentMethod
     * @param EloquentCollection $userPayoutMethods
     * @param EloquentCollection $payoutMethodRequests
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        PaymentMethod $paymentMethod,
        EloquentCollection $userPayoutMethods,
        EloquentCollection $payoutMethodRequests,
        EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var PaymentMethod paymentMethod */
        $this->paymentMethod = $paymentMethod;

        /** @var EloquentCollection userPayoutMethods */
        $this->userPayoutMethods = $userPayoutMethods;

        /** @var EloquentCollection payoutMethodRequests */
        $this->payoutMethodRequests = $payoutMethodRequests;

        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payout_method',
        'user_payout_methods',
        'payout_method_requests'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     */
    public function includePayoutMethod() : Item
    {
        return $this->item(
            $this->paymentMethod,
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
     * @return Collection|null
     */
    public function includePayoutMethodRequests() : ?Collection
    {
        return $this->collection(
            $this->payoutMethodRequests,
            new PayoutMethodRequestTransformer(
                $this->paymentMethodImages
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_pages';
    }
}
