<?php

namespace swl\core;

/**
 * Description of Namespaces
 *
 * @author schivei
 */
class Namespaces extends StateRegister
{

    private $namespaces = [];

    public function __construct()
    {
        static::SetRegister(__CLASS__, $this);
    }

    public function Add($space, $path)
    {
        $path .= $path[\strlen($path) - 1] === \DIRECTORY_SEPARATOR ? '' : \DIRECTORY_SEPARATOR;

        $space = $space[0] === '\\' ? substr($space, 1) : $space;

        $this->namespaces[$space] = $path;
    }

    public function Get($space)
    {
        $space = $space[0] === '\\' ? substr($space, 1) : $space;

        return isset($this->namespaces[$space]) ? $this->namespaces[$space] : null;
    }

    public function Match($space)
    {
        $space = $space[0] === '\\' ? substr($space, 1) : $space;

        $ns = $this->Get($space);
        if ($ns) return $ns;

        foreach ($this->namespaces as $n => $path)
        {
            if (\strlen($space) < \strlen($n)) continue;

            $pattern = "/^({$n}\\)";
            $pat     = str_replace('\\', '\\\\', $pattern . '/');

            if (\preg_match($pat, $space))
            {
                return \str_replace('\\', \DIRECTORY_SEPARATOR,
                                    $path . \str_replace($n . '\\', '', $space));
            }
        }
    }

    /**
     * @return Namespaces
     */
    public static function &GetInstance()
    {
        $inst = static::GetRegister(__CLASS__)? : (new self());

        return $inst;
    }

}
