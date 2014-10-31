<?php

namespace swl\core;

require_once __DIR__ . \DIRECTORY_SEPARATOR . 'StateRegister.php';

/**
 * Description of Loader
 *
 * @author schivei
 */
class Loader
{

    /**
     * @var Namespaces
     */
    private $namespaces;

    private function __construct()
    {
        $this->namespaces = Namespaces::GetInstance();
        StateRegister::SetRegister(__CLASS__, $this);

        /* @var $loader Loader */
        $loader = $this;

        spl_autoload_register(function ($path) use (&$loader)
        {
            /* @var $loader Loader */
            $loader->Load($path);
        }, true, true);
    }

    public function Load($path)
    {
        $newPath = $this->namespaces->Match($path);

        $file = $newPath . '.php';

        if ((!\is_file($file) || \is_dir($file)) && \stripos($path, '\\') !== false)
        {
            throw new \Exception("Não foi possível encontrar a definição de objeto '{$path}'");
        }
        else if (\stripos($path, '\\') !== false)
        {
            require_once $file;
        }
    }

    public static function init()
    {
        new self();
    }

}
