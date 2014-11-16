<?php

namespace swl\core\types;

use \ArrayIterator,
    \InvalidArgumentException,
    \silly\core\collections\Enumerable;

/**
 * Description of String
 *
 * @author schivei
 */
class String extends Enumerable
{

    /**
     * @param \String $value
     * @throws InvalidArgumentException
     */
    public function __construct($value = NULL)
    {
        parent::__construct();

        if (!\is_null($value) && (!\is_string($value) || !($value instanceof \String)))
        {
            throw new InvalidArgumentException(\String::Format("O método %s espera por uma string!",
                                                               __METHOD__));
        }

        $this->value = $value;
    }

    /**
     * @param \String $format likes sprintf function
     * @param mixed $args the substituition values
     * @return \String
     */
    public static function Format($format, ...$args)
    {
        $str = sprintf((string) $format, ...$args);

        return new \String($str);
    }

    public function getIterator()
    {
        return new ArrayIterator(\explode('', $this->value));
    }

}
