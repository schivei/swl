<?php

namespace swl\core\collections;

use \InvalidArgumentException,
    \swl\core\types\TypeObject;

/**
 * Description of KeyValuePair
 *
 * @author schivei
 * @property mixed $Key get key value
 * @property mixed $KeyType get key type
 * @property mixed $Value get value value
 * @property mixed $ValueType get value type
 */
class KeyValuePair extends TypeObject
{

    private $ktype;
    private $key;
    private $vtype;

    public function __construct($t, $te, &$key, &$value)
    {
        parent::__construct();

        if (!class_exists($t) || !class_exists($te))
                throw new InvalidArgumentException("O tipo é inválido ou não foi encontrado.");

        $this->ktype = $t;
        $this->vtype = $te;

        if (!($key instanceof $t))
                throw new InvalidArgumentException("A chave é inválida.");

        if (!($value instanceof $te))
                throw new InvalidArgumentException("O valor é inválida.");

        $this->key   = &$key;
        $this->value = &$value;
    }

    public function &__get($name)
    {
        switch ($name)
        {
            case 'Key':
                return $this->Key;

            case 'KeyType':
                return $this->ktype;

            case 'Value':
                return $this->value;

            case 'ValueType':
                return $this->vtype;
        }
    }

}
