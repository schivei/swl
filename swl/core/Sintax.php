<?php

namespace swl\core;

use \Generator,
    \Generator,
    \Iterator,
    \Iterator,
    \swl\core\collections\Linq,
    \swl\core\collections\Linq,
    \swl\core\exceptions\SintaxException,
    \swl\core\exceptions\SintaxException,
    \swl\core\sintax\SintaxAttribute,
    \swl\core\sintax\SintaxAttribute,
    \swl\core\sintax\SintaxConfiguration,
    \swl\core\sintax\SintaxConfiguration,
    \swl\core\sintax\SintaxController,
    \swl\core\sintax\SintaxController,
    \swl\core\sintax\SintaxCoreRewrite,
    \swl\core\sintax\SintaxCoreRewrite,
    \swl\core\sintax\SintaxDatabase,
    \swl\core\sintax\SintaxEnum,
    \swl\core\sintax\SintaxLibrary,
    \swl\core\sintax\SintaxModel,
    \swl\core\sintax\SintaxModel,
    \swl\core\sintax\SintaxModule,
    \swl\core\sintax\SintaxModule,
    \swl\core\Token;

/**
 * Description of Sintax
 *
 * @author schivei
 */
class Sintax
{

    /**
     * @var \swl\core\Lexer[]
     */
    private $lexers;

    public function __construct(Generator $lexers)
    {
        $this->lexers = $lexers;
    }

    public function run()
    {
        $analizers = $this->analize();
    }

    /**
     * @return Iterator
     */
    private function analize()
    {
        foreach ($this->lexers as $lexer)
        {
            /* @var $tokens Token[] */
            $tokens = $lexer->analize();

            $from   = new Linq($tokens);
            $tokens = $from->Where(function (Token $tok)
                    {
                        return $tok->getType() !== 'T_WHITESPACE' && $tok->getType() !==
                                'T_MULINE_COMMENT' && $tok->getType() !== 'T_SINGLEINE_COMMENT';
                    })->GetIterator();

            /* @var $tok Token */
            $tok = $tokens->current();

            switch ($tok->getType())
            {
                case 'T_CONTROLLER':
                    yield new SintaxController($tokens);
                    break;

                case 'T_DATABASE':
                    yield new SintaxDatabase($tokens);
                    break;

                case 'T_MODEL':
                    yield new SintaxModel($tokens);
                    break;

                case 'T_ATTRIBUTE':
                    yield new SintaxAttribute($tokens);
                    break;

                case 'T_CONFIG':
                    yield new SintaxConfiguration($tokens);
                    break;

                case 'T_ENUM':
                    yield new SintaxEnum($tokens);
                    break;

                case 'T_LIBRARY':
                    yield new SintaxLibrary($tokens);
                    break;

                case 'T_MODULE':
                    yield new SintaxModule($tokens);
                    break;

                case 'T_CORE_REWRITE':
                    yield new SintaxCoreRewrite($tokens);
                    break;

                default:
                    throw new SintaxException("Invalid initial Token in file. Expected: " .
                    Tokens::ExceptInitialFiles(), $tok);
            }
        }
    }

}

/**
ROOT := <T_CONTROLLER> <T_WHITESPACE>+ <T_IDENTIFIER>
       (<T_WHITESPACE>* <T_DOUBLECOMMA> <T_WHITESPACE>* <T_IDENTIFIER>)?
       <T_WHITESPACE>* <T_OPEN_BLOCK> (<CONTROLLER_STMT>*|<T_WHITESPACE>*) <T_CLOSE_BLOCK>

SOME_METHODS_STMT :=

CNTRL_ATT_STMT := <T_OPEN_ARRAY> <T_WHITESPACE>*
                <T_WHITESPACE>* <T_IDENTIFIER> <T_WHITESPACE>*
                (, <T_WHITESPACE>* <T_IDENTIFIER> <T_WHITESPACE>*)*
                <T_WHITESPACE>* <T_CLOSE_ARRAY>

SOME_STRING := (<T_UNESCAPED_STRING>|<T_ESCAPED_STRING>)

ACTION_STMT := <T_WHITESPACE>* <T_ACTION>  <T_WHITESPACE>* <SOME_STRING>? <T_COMMAND_RUN_SEP>
            <T_IDENTIFIER> <T_WHITESPACE>* <T_OPEN_BLOCK> (<T_WHITESPACE>*|<SOME_METHODS_STMT>*)
            <T_CLOSE_BLOCK>

CONTROLLER_STMT := <T_WHITESPACE>*
                  (<T_OPEN_ARRAY> <T_WHITESPACE>* <CNTRL_ATT_STMT> <T_WHITESPACE>*
                    <T_CLOSE_ARRAY> <T_WHITESPACE>*)* <T_WHITESPACE>*
                  <ACTION_STMT>


 *  *
 *

 */