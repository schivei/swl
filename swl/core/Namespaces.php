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
        static::setRegister(__CLASS__, $this);
    }

    public function add($space, $path)
    {
        $path .= $path[\strlen($path) - 1] === \DIRECTORY_SEPARATOR ? '' : \DIRECTORY_SEPARATOR;

        $space = $space[0] === '\\' ? substr($space, 1) : $space;

        $this->namespaces[$space] = $path;
    }

    public function get($space)
    {
        $space = $space[0] === '\\' ? substr($space, 1) : $space;

        return isset($this->namespaces[$space]) ? $this->namespaces[$space] : null;
    }

    public function match($space)
    {
        $space = $space[0] === '\\' ? substr($space, 1) : $space;

        $ns = $this->get($space);
        if (!$ns)
                foreach ($this->namespaces as $n => $path) {
                if (\strlen($space) < \strlen($n)) continue;

                $pattern = "/^({$n}\\)";
                $pat     = str_replace('\\', '\\\\', $pattern . '/');

                if (\preg_match($pat, $space)) {
                    $ns = \str_replace('\\', \DIRECTORY_SEPARATOR,
                                       $path . \str_replace($n . '\\', '',
                                                            $space));
                    break;
                }
            }

        return $ns;
    }

    /**
     * @return Namespaces
     */
    public static function &getInstance()
    {
        $inst = static::getRegister(__CLASS__)? : (new self());

        return $inst;
    }

}
