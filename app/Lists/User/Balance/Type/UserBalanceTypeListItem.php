<?php

namespace App\Lists\User\Balance\Type;

/**
 * Class UserBalanceTypeListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $idPrefix
 * @property int $count
 *
 * @package App\Lists\User\Balance\Type
 */
class UserBalanceTypeListItem
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
     * @var int|null
     */
    public ?int $count;

    /**
     * UserBalanceTypeListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string $idPrefix
     * @param int|null $count
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        string $idPrefix,
        ?int $count = null
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

        /** @var int count */
        $this->count = $count;
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
     * @return int|null
     */
    public function getCount() : ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     *
     * @return void
     */
    public function setCount(
        ?int $count
    ) : void
    {
        $this->count = $count ?: 0;
    }
}