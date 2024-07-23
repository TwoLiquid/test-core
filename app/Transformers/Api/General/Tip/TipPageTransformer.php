<?php

namespace App\Transformers\Api\General\Tip;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Order\OrderItem;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Services\Tip\TipService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class TipPageTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class TipPageTransformer extends BaseTransformer
{
    /**
     * @var OrderItem
     */
    protected OrderItem $orderItem;

    /**
     * @var float
     */
    protected float $amount;

    /**
     * @var float|null
     */
    protected ?float $amountTax;

    /**
     * @var float
     */
    protected float $amountTotal;

    /**
     * @var float|null
     */
    protected ?float $paymentFee;

    /**
     * @var float|null
     */
    protected ?float $paymentFeeTax;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var TipService
     */
    protected TipService $tipService;

    /**
     * TipPageTransformer constructor
     *
     * @param OrderItem $orderItem
     * @param float $amount
     * @param float|null $amountTax
     * @param float $amountTotal
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     */
    public function __construct(
        OrderItem $orderItem,
        float $amount,
        ?float $amountTax,
        float $amountTotal,
        ?float $paymentFee,
        ?float $paymentFeeTax
    )
    {
        /** @var OrderItem orderItem */
        $this->orderItem = $orderItem;

        /** @var float amount */
        $this->amount = $amount;

        /** @var float amountTax */
        $this->amountTax = $amountTax;

        /** @var float amountTax */
        $this->amountTotal = $amountTotal;

        /** @var float paymentFee */
        $this->paymentFee = $paymentFee;

        /** @var float paymentFeeTax */
        $this->paymentFeeTax = $paymentFeeTax;

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var TipService tipService */
        $this->tipService = new TipService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payment_methods',
        'orderItem'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'amount'          => $this->amount,
            'amount_tax'      => $this->amountTax ?: 0,
            'amount_total'    => $this->amountTotal,
            'payment_fee'     => $this->paymentFee ?: 0,
            'payment_fee_tax' => $this->paymentFeeTax ?: 0
        ];
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includePaymentMethods() : ?Collection
    {
        $paymentMethods = $this->paymentMethodRepository->getAll();

        return $this->collection($paymentMethods, new PaymentMethodTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeOrderItem() : ?Item
    {
        return $this->item($this->orderItem, new OrderItemTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_pages';
    }
}
