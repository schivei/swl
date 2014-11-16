<?php

namespace swl\core\collections;

use \InvalidArgumentException;

/**
 * Description of TGeneric
 *
 * @author schivei
 */
trait TGeneric
{

    private function pair($t, $value)
    {
        if (!($value instanceof $t))
                throw new InvalidArgumentException("O tipo é inválido ou não foi encontrado.");
    }

}
