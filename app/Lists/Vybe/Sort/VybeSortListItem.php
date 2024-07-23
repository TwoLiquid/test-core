<?php

namespace App\Lists\Vybe\Sort;

/**
 * Class VybeSortListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Vybe\Sort
 */
class VybeSortListItem
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
     * VybeSortListItem constructor
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
    public function isDate() : bool
    {
        return $this->code == 'date';
    }

    /**
     * @return bool
     */
    public function isPopular() : bool
    {
        return $this->code == 'popular';
    }

    /**
     * @return bool
     */
    public function isTopRated() : bool
    {
        return $this->code == 'top-rated';
    }
}