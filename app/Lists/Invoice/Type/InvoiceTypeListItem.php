<?php

namespace App\Lists\Invoice\Type;

/**
 * Class InvoiceTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $idPrefix
 * @property string $attachment
 *
 * @package App\Lists\Invoice\Type
 */
class InvoiceTypeListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $idPrefix;

    /**
     * @var string|null
     */
    public ?string $attachment;

    /**
     * InvoiceTypeListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string $idPrefix
     * @param string|null $attachment
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        string $idPrefix,
        ?string $attachment
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var string idPrefix */
        $this->idPrefix = $idPrefix;

        /** @var string attachment */
        $this->attachment = $attachment;
    }

    /**
     * @return bool
     */
    public function isBuyer() : bool
    {
        return $this->code == 'buyer';
    }

    /**
     * @return bool
     */
    public function isSeller() : bool
    {
        return $this->code == 'seller';
    }

    /**
     * @return bool
     */
    public function isAffiliate() : bool
    {
        return $this->code == 'affiliate';
    }

    /**
     * @return bool
     */
    public function isCreditBuyer() : bool
    {
        return $this->code == 'credit_buyer';
    }

    /**
     * @return bool
     */
    public function isCreditSeller() : bool
    {
        return $this->code == 'credit_seller';
    }

    /**
     * @return bool
     */
    public function isTipBuyer() : bool
    {
        return $this->code == 'tip_buyer';
    }

    /**
     * @return bool
     */
    public function isTipSeller() : bool
    {
        return $this->code == 'tip_seller';
    }

    /**
     * @return bool
     */
    public function isCustom() : bool
    {
        return $this->code == 'custom';
    }
}