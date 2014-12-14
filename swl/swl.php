<?php

namespace
{

    require_once __DIR__ . '/core/Register.php';
    require_once __DIR__ . '/core/StateRegister.php';
    require_once __DIR__ . '/core/Namespaces.php';
    require_once __DIR__ . '/core/Loader.php';
}

namespace swl
{

    use \swl\core\Namespaces,
        \swl\core\Loader,
        \swl\lib\cli\Console,
        \swl\lib\cli\Colors;

    const FWPATH = __DIR__;

    try
    {

        /* @var $ns Namespaces */
        $ns = Namespaces::GetInstance();
        $ns->Add(__NAMESPACE__, __DIR__);

        Loader::init();

        if (isset($argv) && !\defined('APPPATH'))
        {
            management\cli\Program::Main($argc - 1, ...$argv);
        }
        else
        {
            if (!\defined('APPPATH'))
            {
                throw new \Exception("An error has occurred during application calls.\nCheck your primary code.\n");
            }

            /**
             * @todo implement web script
             */
        }
    }
    catch (\Exception $e)
    {
        \header("HTTP/1.1 500 Internal Server Error");
        \header("Content-Type: text/plain");
        if (Console::IsCLI())
                Console::ColorWriteLine($e->getMessage(), Colors::FG_RED);
        else echo $e->getMessage();
        die();
    }
}