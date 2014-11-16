<?php

namespace swl\core;

/**
 * Description of Register
 *
 * @author schivei
 */
abstract class Register
{

    public abstract static function SetRegister($name, &$value);

    public abstract static function &GetRegister($name);

    public abstract static function UnsetRegister($name);
}
