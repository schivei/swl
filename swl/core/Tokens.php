<?php

namespace swl\core;

final class tokens
{

    public static $_simpleTerminal = [
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
        '/^(\.)$/'          => 'T_CONCATENATE',
        '/^(<)$/'           => 'T_MINOR',
        '/^(>)$/'           => 'T_MAJOR',
        '/^(&)$/'           => 'T_AND',
        '/^(|)$/'           => 'T_OR',
        '/^(@)$/'           => 'T_THIS_OBJECT', // like $this->
        '/^(")$/'           => 'T_DOUBLEQUOTE',
        '/^(\')$/'          => 'T_QUOTE',
        '/^([!])$/'         => 'T_NEGATE',
        '/^([=])$/'         => 'T_EQUAL',
        '/^([+])$/'         => 'T_PLUS',
        '/^([-])$/'         => 'T_MINUS',
        '/^([*])$/'         => 'T_MULTIPLY',
        '/^([\/])$/'        => 'T_DIVIDE',
        '/^([#])$/'         => 'T_HASH'
    ];

    /**
     * Complex terminals
     * @var string[]
     */
    public static $_terminals = [
        '/^(core)$/'                                => 'T_CORE_REWRITE',
        '/^(database)$/'                            => 'T_DATABASE',
        '/^(attribute)$/'                           => 'T_ATTRIBUTE',
        '/^(config)$/'                              => 'T_CONFIG',
        '/^(enum)$/'                                => 'T_ENUM_TYPE',
        '/^(date)$/'                                => 'T_DATE_TYPE',
        '/^(datetime)$/'                            => 'T_DATETIME_TYPE',
        '/^(string)$/'                              => 'T_STRING_TYPE',
        '/^(object)$/'                              => 'T_OBJECT_TYPE',
        '/^(int)$/'                                 => 'T_INTEGER_TYPE',
        '/^(float)$/'                               => 'T_FLOAT_TYPE',
        '/^(bool)$/'                                => 'T_BOOLEAN_TYPE',
        '/^(regexp)$/'                              => 'T_REGEXP_TYPE',
        '/^(query)$/'                               => 'T_QUERY_TYPE',
        '/^(type)$/'                                => 'T_RETYPE',
        '/^(operator)$/'                            => 'T_NEW_OPERATOR',
        '/^(new)$/'                                 => 'T_NEW_CALL',
        '/^(try)$/'                                 => 'T_TRY',
        '/^(catch)$/'                               => 'T_CATCH',
        '/^(throw)$/'                               => 'T_THROW',
        '/^(lib)$/'                                 => 'T_LIBRARY',
        '/^(module)$/'                              => 'T_MODULE',
        '/^(for)$/'                                 => 'T_FOR',
        '/^(in)$/'                                  => 'T_FORIN',
        '/^(while)$/'                               => 'T_WHILE',
        '/^(do)$/'                                  => 'T_DO',
        '/^(route)$/'                               => 'T_ROUTE',
        '/^(on)$/'                                  => 'T_ON_COMMAND',
        '/^(get)$/'                                 => 'T_FIELD_GET',
        '/^(set)$/'                                 => 'T_FIELD_SET',
        '/^(filter)$/'                              => 'T_FIELD_COMMAND_FILTER',
        '/^(validate)$/'                            => 'T_FIELD_COMMAND_VALIDATION',
        '/^(is)$/'                                  => 'T_IS_COMMAND',
        '/^(controller)$/'                          => 'T_CONTROLLER',
        '/^(model)$/'                               => 'T_MODEL',
        '/^(action)$/'                              => 'T_ACTION',
        '/^(event)$/'                               => 'T_EVENT',
        '/^(reg\/)([^\n\r/]+|\\[^\n\r]+)(\/)(i)?$/' => 'T_REGEXP',
        '/^(env)$/'                                 => 'T_ENV',
        '/^(.+)$/'                                  => 'T_IDENTIFIER'
    ];

    private function __construct()
    {

    }

}
