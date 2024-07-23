<?php

namespace App\Transformers\Api\Admin\Auth;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TwoFactorTransformer
 *
 * @package App\Transformers\Api\Admin\Auth
 */
class TwoFactorTransformer extends BaseTransformer
{
    /**
     * @var string
     */
    protected string $secretKey;

    /**
     * @var string
     */
    protected string $qrCode;

    /**
     * TwoFactorTransformer constructor
     *
     * @param string $secretKey
     * @param string $qrCode
     */
    public function __construct(
        string $secretKey,
        string $qrCode
    )
    {
        $this->secretKey = $secretKey;
        $this->qrCode = $qrCode;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'qr_code'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'secret_key' => $this->secretKey
        ];
    }

    /**
     * @return Item|null
     */
    public function includeQrCode() : ?Item
    {
        return $this->qrCode ? $this->item([], new QrCodeTransformer($this->qrCode)) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'two_factor';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'two_factors';
    }
}
