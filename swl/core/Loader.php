<?php

namespace swl\core;

/**
 * Description of Loader
 *
 * @author schivei
 */
class Loader
{

    /**
     * @var \swl\core\Namespaces
     */
    private $namespaces;

    private function __construct()
    {
        $this->namespaces = \swl\core\Namespaces::getInstance();
        \swl\core\StateRegister::setRegister(__CLASS__, $this);

        /* @var $loader \swl\core\Loader */
        $loader = $this;

        spl_autoload_register(function ($path) use (&$loader)
        {
            /* @var $loader \swl\core\Loader */
            $loader->load($path);
        }, true, true);
    }

    public function load($path)
    {
        $newPath = $this->namespaces->match($path);

        $file = $newPath . '.php';

        $pattern = '/^(\\\\)?(SebastianBergmann)/';

        if (!preg_match($pattern, $path) && (!\is_file($file) || \is_dir($file)) &&
                \stripos($path, '\\') !== false)
        {
            //throw new Exception("Não foi possível encontrar a definição de objeto '{$path}'");
            return;
        }
        else if (\stripos($path, '\\') !== false && !preg_match($pattern, $path))
        {
            require_once $file;
        }
    }

    public static function init()
    {
        new self();
    }

}
