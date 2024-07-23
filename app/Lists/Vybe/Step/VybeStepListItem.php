<?php

namespace App\Lists\Vybe\Step;

/**
 * Class VybeStepListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Vybe\Step
 */
class VybeStepListItem
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
     * VybeStepListItem constructor
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
        /** @var string id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isFirst() : bool
    {
        return $this->code == 'first';
    }

    /**
     * @return bool
     */
    public function isSecond() : bool
    {
        return $this->code == 'second';
    }

    /**
     * @return bool
     */
    public function isThird() : bool
    {
        return $this->code == 'third';
    }

    /**
     * @return bool
     */
    public function isFourth() : bool
    {
        return $this->code == 'fourth';
    }

    /**
     * @return bool
     */
    public function isFifth() : bool
    {
        return $this->code == 'fifth';
    }

    /**
     * @return bool
     */
    public function isCompleted() : bool
    {
        return $this->code == 'completed';
    }
}