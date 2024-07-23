<?php

namespace App\Lists\Currency;

/**
 * Class CurrencyListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Currency
 */
class CurrencyListItem
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
     * CurrencyListItem constructor
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
    public function isUsd() : bool
    {
        return $this->code == 'usd';
    }

    /**
     * @return bool
     */
    public function isAud() : bool
    {
        return $this->code == 'aud';
    }

    /**
     * @return bool
     */
    public function isBgn() : bool
    {
        return $this->code == 'bgn';
    }

    /**
     * @return bool
     */
    public function isBrl() : bool
    {
        return $this->code == 'brl';
    }

    /**
     * @return bool
     */
    public function isCad() : bool
    {
        return $this->code == 'cad';
    }

    /**
     * @return bool
     */
    public function isChf() : bool
    {
        return $this->code == 'chf';
    }

    /**
     * @return bool
     */
    public function isCny() : bool
    {
        return $this->code == 'cny';
    }

    /**
     * @return bool
     */
    public function isCzk() : bool
    {
        return $this->code == 'czk';
    }

    /**
     * @return bool
     */
    public function isDkk() : bool
    {
        return $this->code == 'dkk';
    }

    /**
     * @return bool
     */
    public function isEur() : bool
    {
        return $this->code == 'eur';
    }

    /**
     * @return bool
     */
    public function isGbr() : bool
    {
        return $this->code == 'gbr';
    }

    /**
     * @return bool
     */
    public function isHkd() : bool
    {
        return $this->code == 'hkd';
    }

    /**
     * @return bool
     */
    public function isHuf() : bool
    {
        return $this->code == 'huf';
    }

    /**
     * @return bool
     */
    public function isIdr() : bool
    {
        return $this->code == 'idr';
    }

    /**
     * @return bool
     */
    public function isInr() : bool
    {
        return $this->code == 'inr';
    }

    /**
     * @return bool
     */
    public function is() : bool
    {
        return $this->code == '';
    }

    /**
     * @return bool
     */
    public function isJpy() : bool
    {
        return $this->code == 'jpy';
    }

    /**
     * @return bool
     */
    public function isMyr() : bool
    {
        return $this->code == 'myr';
    }

    /**
     * @return bool
     */
    public function isNok() : bool
    {
        return $this->code == 'nok';
    }

    /**
     * @return bool
     */
    public function isNzd() : bool
    {
        return $this->code == 'nzd';
    }

    /**
     * @return bool
     */
    public function isPln() : bool
    {
        return $this->code == 'pln';
    }

    /**
     * @return bool
     */
    public function isRon() : bool
    {
        return $this->code == 'ron';
    }

    /**
     * @return bool
     */
    public function isTry() : bool
    {
        return $this->code == 'try';
    }

    /**
     * @return bool
     */
    public function isSek() : bool
    {
        return $this->code == 'sek';
    }

    /**
     * @return bool
     */
    public function isSgd() : bool
    {
        return $this->code == 'sgd';
    }
}