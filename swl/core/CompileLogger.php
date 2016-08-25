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

    public static function &getRegister($name)
    {
        $reg = &self::$registry[$name];
        return $reg;
    }

    public static function setRegister($name, &$value)
    {
        self::$registry[$name] = &$value;
    }

    public static function unsetRegister($name)
    {
        if (isset(self::$registry[$name])) unset(self::$registry[$name]);
    }

}
