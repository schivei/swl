<?php

namespace swl\management\cli;

use \swl\lib\cli\Colors,
    \swl\lib\cli\Console,
    \swl\management\_private\Commands;

/**
 * Description of Program
 *
 * @author schivei
 */
final class Program
{

    public static function Main($argc, $program, ...$argv)
    {
        Console::SetColumns(80);

        $program = new \swl\management\cli\Program();

        if (!$argc || $argv[0] === '-h' || $argv[0] === '--help')
        {
            if (!$argc)
            {
                Console::ColorWriteLine("Please, insert a command!",
                                        Colors::FG_RED);
                Console::WriteLine();
                Console::WriteLine();
            }

            $program->showHelp();
        }
        else
        {
            $program->run($argv);
        }
    }

    public function showHelp()
    {
        Console::ColorWrite("SWL - Simple Web Language: ", Colors::FG_BROWN);
        Console::ColorWriteLine("v0.1-development", Colors::FG_GREEN);
        Console::WriteLine("Uses: swl <command>");
        Console::ColorWriteLine("COMMANDS", Colors::FG_BLUE);
        Console::WriteLine();
        Console::ColorWrite("%-30s", Colors::FG_YELLOW, 'create project [name]');
        Console::WriteLine('to create a new project at the same directory');
        Console::WriteLine();
        Console::ColorWrite("%-30s", Colors::FG_YELLOW, 'compile');
        Console::WriteLine('to compile the current project');
        Console::WriteLine();
        Console::ColorWrite("%-30s", Colors::FG_YELLOW, 'check [filename]');
        Console::WriteLine('to check lex and sintax of the single line');
        Console::WriteLine('%30s%s', '', 'No compile');
        Console::WriteLine();
        Console::ColorWrite("%-30s", Colors::FG_YELLOW, 'public [location]');
        Console::WriteLine('to publish the current project to new location');
        Console::WriteLine('%30s%s', '',
                           'All extra files and SWL files are removed');
        Console::WriteLine();
        Console::ColorWrite("%-30s", Colors::FG_YELLOW, '-v or --version');
        Console::WriteLine('to show SWL version.');
        Console::WriteLine();
        Console::ColorWrite("%-30s", Colors::FG_YELLOW, '-h or --help');
        Console::WriteLine('to show this helper message');

        Console::WriteLine();
    }

    public function run($commands)
    {
        try
        {
            $cs = implode(' ', $commands);

            if ($cs === '-v' || $cs === '--version')
            {
                Console::ColorWrite("SWL - Simple Web Language: ",
                                    Colors::FG_BROWN);
                Console::ColorWriteLine("v0.1-development", Colors::FG_GREEN);
            }
            else
            {
                $cmd = new Commands($cs);

                $cmd->run();
            }
        }
        catch (\BadMethodCallException $ex)
        {
            Console::ColorWriteLine($ex->getMessage(), Colors::FG_RED);
            Console::WriteLine();
            $this->showHelp();
        }
    }

}
