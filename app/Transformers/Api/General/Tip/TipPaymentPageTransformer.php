<?php

namespace App\Transformers\Api\General\Tip;

use App\Models\MySql\Tip\Tip;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipPaymentPageTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class TipPaymentPageTransformer extends BaseTransformer
{
    /**
     * @var Tip
     */
    protected Tip $tip;

    /**
     * @var string|null
     */
    protected ?string $hash;

    /**
     * @var string|null
     */
    protected ?string $paymentUrl;

    /**
     * TipPaymentPageTransformer constructor
     *
     * @param Tip $tip
     * @param string|null $hash
     * @param string|null $paymentUrl
     */
    public function __construct(
        Tip $tip,
        ?string $hash = null,
        ?string $paymentUrl = null
    )
    {
        /** @var Tip tip */
        $this->tip = $tip;

        /** @var string hash */
        $this->hash = $hash;

        /** @var string paymentUrl */
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'tip'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'hash'        => $this->hash,
            'payment_url' => $this->paymentUrl
        ];
    }

    /**
     * @return Item|null
     */
    public function includeTip() : ?Item
    {
        $tip = $this->tip;

        return $this->item($tip, new TipTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_payment_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_payment_pages';
    }
}
