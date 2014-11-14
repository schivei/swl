<?php

namespace swl\core\collections;

use \InvalidArgumentException,
    \swl\core\collections\Enumerable,
    \swl\core\collections\KeyValuePair;

/**
 * Description of Dictionary
 *
 * @author schivei
 */
class Dictionary extends Enumerable
{

    use TGeneric;

    private $ktype;
    private $vtype;

    public function __construct($t, $te)
    {
        $list = [];

        parent::__construct($list);

        if (!class_exists($t) || !class_exists($te))
                throw new InvalidArgumentException("O tipo é inválido ou não foi encontrado.");

        $this->ktype = $t;
        $this->vtype = $te;
    }

    public function add($key, $value)
    {
        $this->pair($this->ktype, $key);
        $this->pair($this->vtype, $value);

        $kvp    = new KeyValuePair($this->ktype, $this->vtype, $key, $value);
        $this[] = $kvp;
    }

    public function remove($key)
    {
        $this->pair($this->ktype, $key);

        $i = -1;
        foreach ($this as $k => $value)
        {
            /* @var $kvp KeyValuePair */
            $kvp = $value;

            if ($kvp->key === $key)
            {
                $i = $k;
                break;
            }
        }

        if ($this->offsetExists($i)) $this->offsetUnset($i);
    }

}
