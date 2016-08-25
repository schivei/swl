<?php

namespace swl\core\types;

use \ArrayIterator,
    \InvalidArgumentException;

/**
 * Description of String
 *
 * @author schivei
 */
class TypeString extends \swl\core\collections\Enumerable
{

    /**
     * @param \String $value
     * @throws InvalidArgumentException
     */
    public function __construct($value = NULL)
    {
        if (!\is_null($value) && (!\is_string($value) || !($value instanceof \String))) {
            throw new InvalidArgumentException(\String::Format("O método %s espera por uma string!",
                                                               __METHOD__));
        }

        $value = (string) $value;

        $from = \strlen($value) === 0 ? [] : \str_split($value);

        parent::__construct($from);
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

}
