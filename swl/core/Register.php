<?php

namespace swl\core;

/**
 * Description of Register
 *
 * @author schivei
 */
abstract class Register
{

    public static function SetRegister($name, &$value)
    {
        throw new \BadMethodCallException("Not implemented method.");
    }

    public static function &GetRegister($name)
    {
        throw new \BadMethodCallException("Not implemented method.");
    }

    public static function UnsetRegister($name)
    {
        throw new \BadMethodCallException("Not implemented method.");
    }

}
