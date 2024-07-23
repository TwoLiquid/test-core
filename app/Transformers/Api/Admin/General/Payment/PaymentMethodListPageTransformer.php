<?php

namespace App\Transformers\Api\Admin\General\Payment;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class PaymentMethodListPageTransformer
 *
 * @package App\Transformers\Api\Admin\General\Payment
 */
class PaymentMethodListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $paymentMethods;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PaymentMethodListPageTransformer constructor
     *
     * @param EloquentCollection $paymentMethods
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        EloquentCollection $paymentMethods,
        ?EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var EloquentCollection paymentMethods */
        $this->paymentMethods = $paymentMethods;

        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payment_methods'
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
    public function includePaymentMethods() : ?Collection
    {
        $paymentMethods = $this->paymentMethods;

        return $this->collection($paymentMethods, new PaymentMethodTransformer(
            $this->paymentMethodImages
        ));
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_method_list_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_method_list_pages';
    }
}
