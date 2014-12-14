<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace swl\lib\cli;

\define("\EOL", \preg_match('/(win)/i', \PHP_OS) ? "\r\n" : "\n");

/**
 * Description of Console
 *
 * @author Elton Schivei Costa <costa@schivei.nom.br>
 */
final class Console
{

    private static $_columns;
    private static $_lastWrite = '';

    private function __construct()
    {

    }

    public static function IsCLI()
    {
        $argc = \filter_input(\INPUT_SERVER, 'argc');
        $argv = \filter_input(\INPUT_SERVER, 'argv');

        return (\php_sapi_name() === 'cli' || ($argc && $argv && $argc === \count($argv)) ||
                \defined('STDIN') || !\filter_input(\INPUT_SERVER,
                                                    'REQUEST_METHOD'));
    }

    /**
     * @return void
     * @throws \BadMethodCallException
     */
    private static function ValidateCLI()
    {
        if (!static::IsCLI())
                throw new \BadMethodCallException("The Console Library only run under CLI.");
    }

    public static function Write($message, ...$composes)
    {
        $composes = $composes? : [];

        static::ColorWrite($message, null, ...$composes);
    }

    public static function WriteLine($message = '', ...$composes)
    {
        $composes = $composes? : [];

        static::ColorWrite($message, null, ...$composes);

        static::$_lastWrite = '';

        echo EOL;
    }

    public static function ColorWrite($message, $color = null, ...$composes)
    {
        static::ValidateCLI();

        $composes = $composes? : [];
        $message  = sprintf($message, ...$composes);

        echo Colors::getColoredString(static::FormatString($message), $color);
    }

    private static function FormatString($string)
    {
        if (!static::$_columns || strlen(static::$_lastWrite . $string) <= static::$_columns)
        {
            static::$_lastWrite .= $string;
            return $string;
        }

        $str = '';

        if (strlen(static::$_lastWrite . $string) < static::$_columns)
        {
            $str = \substr($string, 0, static::$_columns);
        }
        else
        {
            if (\strlen(static::$_lastWrite) === static::$_columns)
            {
                echo \EOL;
            }
            else
            {
                $cols = static::$_columns - \strlen(static::$_lastWrite);
                $str  = \substr($string, 0, $cols);
            }
        }

        $rest = \str_replace($str, '', $string);

        $att = '';

        while (\strlen($rest) > 0)
        {
            $str .= "\n";

            if (\strlen($rest) <= static::$_columns) $att = $rest;
            else $att = \substr($rest, 0, static::$_columns);

            $rest = \str_replace($att, '', $rest);

            $str .= $att;
        }

        static::$_lastWrite = $att;

        return $str;
    }

    public static function ColorWriteLine($message = '', $color = null,
                                          ...$composes)
    {
        $composes = $composes? : [];

        static::ColorWrite($message, $color, ...$composes);

        static::$_lastWrite = '';

        echo \EOL;
    }

    public static function SetColumns($columns)
    {
        static::$_columns = $columns;
    }

}
