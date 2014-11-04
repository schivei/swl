<?php

namespace swl\core;

/**
 * Description of CompileLogger
 *
 * @author schivei
 */
class CompileLogger extends Register
{

    private static $registry = [];

    public static function GetRegister($name)
    {
        return self::$registry[$name];
    }

    public static function SetRegister($name, &$value)
    {
        self::$registry[$name] = &$value;
    }

    public static function UnsetRegister($name)
    {
        if (isset(self::$registry[$name])) unset(self::$registry[$name]);
    }

}
