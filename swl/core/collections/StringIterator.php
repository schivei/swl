<?php

namespace swl\core\collections;

/**
 * Description of StringIterator
 *
 * @author Elton Schivei Costa <costa@elton.schivei.nom.br>
 */
class StringIterator extends \ArrayIterator
{

    public function __construct($str)
    {
        $str   = (string) ($str);
        $chars = \str_split($str);

        parent::__construct($chars);
    }

    /**
     * Gets the next character from string
     * @return string Returns character if success otherwise null
     */
    public function getNextChar()
    {
        return $this->offsetExists($this->key() + 1) ?
                $this->offsetGet($this->key() + 1) :
                null;
    }

}
