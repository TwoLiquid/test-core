<?php 

namespace App\Lists\Vybe\AgeLimit;

/**
 * Class VybeAgeLimitListItem
 * 
 * @property int id
 * @property string code
 * @property string name
 * 
 * @package App\Lists\Vybe\AgeLimit
 */
class VybeAgeLimitListItem
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
     * VybeAgeLimitListItem constructor
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
    public function isZeroPlus() : bool
    {
        return $this->code == '0';
    }

    /**
     * @return bool
     */
    public function isSixteenPlus() : bool
    {
        return $this->code == '16';
    }

    /**
     * @return bool
     */
    public function isEighteenPlus() : bool
    {
        return $this->code == '18';
    }
}
