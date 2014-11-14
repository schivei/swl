<?php

namespace swl\core\types;

/**
 * Description of Object
 *
 * @author schivei
 */
class Object
{

    protected $value;

    public function __construct()
    {
        $this->value = NULL;
    }

    public function __toString()
    {
        return (string) ($this->value? : static::class);
    }

}
