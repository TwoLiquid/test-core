<?php

namespace App\Transformers\Api\Admin\General\Payment;

use App\Models\MySql\Payment\PaymentMethod;
use App\Transformers\Api\Admin\General\Payment\Form\FormTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PaymentMethodListPageTransformer
 *
 * @package App\Transformers\Api\Admin\General\Payment
 */
class PaymentMethodPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethodImages;

    /**
     * PaymentMethodListPageTransformer constructor
     *
     * @param EloquentCollection|null $paymentMethodImages
     */
    public function __construct(
        ?EloquentCollection $paymentMethodImages = null
    )
    {
        /** @var EloquentCollection paymentMethodImages */
        $this->paymentMethodImages = $paymentMethodImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'payment_method'
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
     * @return Collection|null
     */
    public function includePaymentMethod(PaymentMethod $paymentMethod) : ?Item
    {
        return $this->item($paymentMethod, new PaymentMethodTransformer($this->paymentMethodImages));
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
