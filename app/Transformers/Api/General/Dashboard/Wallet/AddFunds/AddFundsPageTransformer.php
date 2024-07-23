<?php

namespace App\Transformers\Api\General\Dashboard\Wallet\AddFunds;

use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class AddFundsPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Wallet\AddFunds
 */
class AddFundsPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $paymentMethods;

    /**
     * @var AddFundsReceipt|null
     */
    protected ?AddFundsReceipt $addFundsReceipt;

    /**
     * @var string|null
     */
    protected ?string $paymentUrl;

    /**
     * AddFundsPageTransformer constructor
     *
     * @param EloquentCollection|null $paymentMethods
     * @param AddFundsReceipt|null $addFundsReceipt
     * @param string|null $paymentUrl
     */
    public function __construct(
        ?EloquentCollection $paymentMethods,
        ?AddFundsReceipt $addFundsReceipt,
        ?string $paymentUrl
    )
    {
        /** @var EloquentCollection paymentMethods */
        $this->paymentMethods = $paymentMethods;

        /** @var AddFundsReceipt addFundsReceipt */
        $this->addFundsReceipt = $addFundsReceipt;

        /** @var string paymentUrl */
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payment_methods',
        'settings',
        'add_funds_receipt'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'payment_url' => $this->paymentUrl
        ];
    }

    /**
     * @return Collection|null
     */
    public function includePaymentMethods() : ?Collection
    {
        $paymentMethods = $this->paymentMethods;

        return $paymentMethods ? $this->collection($paymentMethods, new PaymentMethodTransformer) : null;
    }

    /**
     * @return Item
     */
    public function includeSettings() : Item
    {
        return $this->item([], new SettingsTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeAddFundsReceipt() : ?Item
    {
        $addFundsReceipt = $this->addFundsReceipt;

        return $addFundsReceipt ? $this->item($addFundsReceipt, new AddFundsReceiptTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_pages';
    }
}
