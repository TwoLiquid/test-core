<?php

namespace App\Transformers\Api\Admin\Auth;

use App\Transformers\BaseTransformer;

/**
 * Class QrCodeTransformer
 *
 * @package App\Transformers\Api\Admin\Auth
 */
class QrCodeTransformer extends BaseTransformer
{
    /**
     * @var string
     */
    protected string $qrCode;

    /**
     * QrCodeTransformer constructor
     *
     * @param string $qrCode
     */
    public function __construct(
        string $qrCode
    )
    {
        $this->qrCode = $qrCode;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'content'   => $this->qrCode,
            'mime'      => 'image/png',
            'extension' => 'png'
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'qrCode';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'qrCodes';
    }
}
