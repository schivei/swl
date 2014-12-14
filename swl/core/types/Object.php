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

    /**
     * @assert () == Object::class
     */
    public function __construct()
    {
        $this->value = NULL;
    }

    /**
     * @assert () == Object::class
     */
    public function __toString()
    {
        return (string) ($this->value? : static::class);
    }

}
