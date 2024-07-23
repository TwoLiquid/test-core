<?php

namespace App\Lists\Payment\Method\Field\Type;

/**
 * Class PaymentMethodFieldTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Payment\Method\Field\Type
 */
class PaymentMethodFieldTypeListItem
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
     * PaymentMethodFieldTypeListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     */
    public function __construct(
        int $id,
        string $code,
        string $name
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isString() : bool
    {
        return $this->code == 'string';
    }

    /**
     * @return bool
     */
    public function isInteger() : bool
    {
        return $this->code == 'integer';
    }

    /**
     * @return bool
     */
    public function isBoolean() : bool
    {
        return $this->code == 'boolean';
    }
}