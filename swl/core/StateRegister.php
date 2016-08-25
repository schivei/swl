<?php

namespace swl\core;

/**
 * Description of StateRegister
 *
 * @author schivei
 */
class StateRegister extends Register
{

    private static $registry = [];

    public static function setRegister($name, &$value)
    {
        self::$registry[$name] = &$value;
    }

    public static function &getRegister($name)
    {
        return self::$registry[$name];
    }

    public static function unsetRegister($name)
    {
        if (isset(self::$registry[$name])) unset(self::$registry[$name]);
    }

}
