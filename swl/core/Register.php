<?php

namespace swl\core;

/**
 * Description of Register
 *
 * @author schivei
 */
abstract class Register
{

    public static function setRegister($name, &$value)
    {
        $value = $value;
        throw new \BadMethodCallException("Not implemented method.\nCan not register {$name}");
    }

    public static function &getRegister($name)
    {
        throw new \BadMethodCallException("Not implemented method.\nThe register '{$name}' do not exists");
    }

    public static function unsetRegister($name)
    {
        throw new \BadMethodCallException("Not implemented method.\nThe register '{$name}' do not exists");
    }

}
