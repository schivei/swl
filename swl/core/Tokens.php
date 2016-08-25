<?php

namespace swl\core;

final class Tokens
{

    public static $simpleTerminal = [
        '/^(:)$/'           => 'T_DOUBLECOMMA',
        '/^(,)$/'           => 'T_COLLON',
        '/^(;)$/'           => 'T_EOL',
        '/^(\[)$/'          => 'T_OPEN_ARRAY',
        '/^(\])$/'          => 'T_CLOSE_ARRAY',
        '/^(\{)$/'          => 'T_OPEN_BLOCK',
        '/^(\})$/'          => 'T_CLOSE_BLOCK',
        '/^(\()$/'          => 'T_OPEN_STATEMENT',
        '/^(\))$/'          => 'T_CLOSE_STATEMENT',
        '/^([ \n\r\t\f])$/' => 'T_WHITESPACE',
        '/^(\.)$/'          => 'T_OBJ_ACCESSOR',
        '/^(<)$/'           => 'T_MINOR',
        '/^(>)$/'           => 'T_MAJOR',
        '/^(&)$/'           => 'T_BITWISE_AND',
        '/^(\|)$/'          => 'T_BITWISE_OR',
        '/^(@)$/'           => 'T_THIS_OBJECT', // like $this->
        '/^(")$/'           => 'T_DOUBLEQUOTE',
        '/^(\')$/'          => 'T_QUOTE',
        '/^([!])$/'         => 'T_NEGATE',
        '/^([=])$/'         => 'T_EQUAL',
        '/^([+])$/'         => 'T_PLUS',
        '/^([-])$/'         => 'T_MINUS',
        '/^([*])$/'         => 'T_MULTIPLY',
        '/^([\/])$/'        => 'T_DIVIDE',
        '/^([#])$/'         => 'T_HASH',
        '/^(\^)$/'          => 'T_BITWISE_XOR',
        '/^([%])$/'         => 'T_MOD'
    ];

    /**
     * Special Terminals
     * @var string[]
     */
    public static $specialTerminals = [
        '/^(=>)$/'   => 'T_ARRAY_ASSOC',
        '/^(->)$/'   => 'T_COMMAND_RUN_SEP',
        '/^(::)$/'   => 'T_STATIC_CALL',
        '/^(\+\+)$/' => 'T_SELF_SUM',
        '/^(--)$/'   => 'T_SELF_SUB',
        '/^(\*\*)$/' => 'T_EXP',
        '/^(&&)$/'   => 'T_LOGICAL_AND',
        '/^(\|\|)$/' => 'T_LOGICAL_OR',
        '/^(@@)$/'   => 'T_PARENT_CALL',
        '/^(==)$/'   => 'T_LOGICAL_EQUAL',
        '/^(<<)$/'   => 'T_BITWISE_MINUS',
        '/^(>>)$/'   => 'T_BITWISE_PLUS',
        '/^(%%)$/'   => 'T_SQRT',
        '/^(!=)$/'   => 'T_LOGICAL_DIF'
    ];

    /**
     * Complex terminals
     * @var string[]
     */
    public static $terminals = [
        '/^(core)$/'                                   => 'T_CORE_REWRITE',
        '/^(database)$/'                               => 'T_DATABASE',
        '/^(attribute)$/'                              => 'T_ATTRIBUTE',
        '/^(config)$/'                                 => 'T_CONFIG',
        '/^(enum)$/'                                   => 'T_ENUM_TYPE',
        '/^(date)$/'                                   => 'T_DATE_TYPE',
        '/^(datetime)$/'                               => 'T_DATETIME_TYPE',
        '/^(string)$/'                                 => 'T_STRING_TYPE',
        '/^(object)$/'                                 => 'T_OBJECT_TYPE',
        '/^(int)$/'                                    => 'T_INTEGER_TYPE',
        '/^(float)$/'                                  => 'T_FLOAT_TYPE',
        '/^(bool)$/'                                   => 'T_BOOLEAN_TYPE',
        '/^(regexp)$/'                                 => 'T_REGEXP_TYPE',
        '/^(query)$/'                                  => 'T_QUERY_TYPE',
        '/^(type)$/'                                   => 'T_RETYPE',
        '/^(operator)$/'                               => 'T_NEW_OPERATOR',
        '/^(new)$/'                                    => 'T_NEW_CALL',
        '/^(try)$/'                                    => 'T_TRY',
        '/^(catch)$/'                                  => 'T_CATCH',
        '/^(throw)$/'                                  => 'T_THROW',
        '/^(library)$/'                                => 'T_LIBRARY',
        '/^(module)$/'                                 => 'T_MODULE',
        '/^(for)$/'                                    => 'T_FOR',
        '/^(in)$/'                                     => 'T_FORIN',
        '/^(while)$/'                                  => 'T_WHILE',
        '/^(do)$/'                                     => 'T_DO',
        '/^(on)$/'                                     => 'T_ON_COMMAND',
        '/^(get)$/'                                    => 'T_FIELD_GET',
        '/^(set)$/'                                    => 'T_FIELD_SET',
        '/^(filter)$/'                                 => 'T_FIELD_COMMAND_FILTER',
        '/^(validate)$/'                               => 'T_FIELD_COMMAND_VALIDATION',
        '/^(is)$/'                                     => 'T_IS_COMMAND',
        '/^(controller)$/'                             => 'T_CONTROLLER',
        '/^(model)$/'                                  => 'T_MODEL',
        '/^(action)$/'                                 => 'T_ACTION',
        '/^(event)$/'                                  => 'T_EVENT',
        '/^(reg\/)([^\n\r\/]+|(\\[^\n\r])+)(\/)(i)?$/' => 'T_REGEXP',
        '/^(env)$/'                                    => 'T_ENV',
        '/^([_a-zA-Z][_a-zA-Z0-9]*)$/'                 => 'T_IDENTIFIER',
        '/^([\'])([^\']+|\\.+)([\'])$/'                => 'T_UNESCAPED_STRING',
        '/^(["])([^"]+|\\.+)(["])$/'                   => 'T_ESCAPED_STRING',
        '/^([-])?([0-9]+)([Ee][-+][0-9]+)?$/'          => 'T_INTEGER',
        '/^([-])?([0-9]+\.[0-9]+)([Ee][-+][0-9]+)?$/'  => 'T_FLOAT',
        '/^(.+)$/'                                     => 'T_ANY'
    ];

    /**
     * @param string $sequence
     * @param int $line
     * @param int $initialPosition
     * @return \swl\core\Token
     * @assert ("a", 1, 0) == null
     * @assert ("::", 1, 0) == new Token("T_STATIC_CALL", "::", 1, 0)
     */
    public static function doubleAnalize($sequence, $line, $initialPosition,
                                         $file)
    {
        $token = null;

        $filtered = \array_filter(\array_keys(static::$specialTerminals),
                                              function($pattern) use ($sequence)
        {
            return !!\preg_match($pattern, $sequence);
        });

        if (\count($filtered) > 0)
                $token = new Token(static::$specialTerminals[\current($filtered)],
                                                                      $sequence,
                                                                      $line,
                                                                      $initialPosition,
                                                                      $file);

        return $token;
    }

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        
    }

    /**
     * @param string $token
     * @return string
     * @assert ("T_STATIC_CALL") == '/^(::)$/'
     * @assert ("T_CONTROLLER") == '/^(controller)$/'
     */
    public static function getPattern($token)
    {
        $keys = [null];
        if (\in_array($token, self::$simpleTerminal))
                $keys = \array_keys(self::$simpleTerminal, $token);
        else if (\in_array($token, self::$specialTerminals))
                $keys = \array_keys(self::$specialTerminals, $token);
        else if (\in_array($token, self::$terminals))
                $keys = \array_keys(self::$terminals, $token);

        return \current($keys);
    }

    public static function exceptInitialFiles()
    {
        return "controller, model, core, attribute, database, config, module, "
                . "library or enum token.";
    }

}
