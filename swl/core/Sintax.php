<?php

namespace swl\core;

use \Generator,
    \Generator,
    \Iterator,
    \Iterator,
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

    /**
     * @var sintax\ISintax[]
     */
    private $analisis;

    public function __construct(Generator $lexers)
    {
        $this->lexers = $lexers;
    }

    public function run()
    {
        $this->analisis = $this->analize();
    }

    /**
     * @return Iterator
     */
    private function analize()
    {
        foreach ($this->lexers as $lexer)
        {
            $lex = new \swl\core\LexerCombinations($lexer);

            /* @var $tokens Token[] */
            $tokens = $lex->GetTokens();

            /* @var $tok Token */
            $token = null;

            foreach ($tokens as $tok)
            {
                switch ($tok->getType())
                {
                    case 'T_CONTROLLER':
                        $token = new SintaxController($lex);
                        break;

                    case 'T_DATABASE':
                        $token = new SintaxDatabase($lex);
                        break;

                    case 'T_MODEL':
                        $token = new SintaxModel($lex);
                        break;

                    case 'T_ATTRIBUTE':
                        $token = new SintaxAttribute($lex);
                        break;

                    case 'T_CONFIG':
                        $token = new SintaxConfiguration($lex);
                        break;

                    case 'T_ENUM':
                        $token = new SintaxEnum($lex);
                        break;

                    case 'T_LIBRARY':
                        $token = new SintaxLibrary($lex);
                        break;

                    case 'T_MODULE':
                        $token = new SintaxModule($lex);
                        break;

                    case 'T_CORE_REWRITE':
                        $token = new SintaxCoreRewrite($lex);
                        break;
                }

                if ($tok->isComment() || $tok->isWhitespace())
                {
                    $token = null;
                    continue;
                }
                else if (\is_null($token))
                {
                    $token = null;
                    break;
                }
            }

            if (\is_null($token))
            {
                throw new SintaxException(
                "Invalid initial Token in file. Expected: " .
                \swl\core\Tokens::ExceptInitialFiles(), $tok);
            }
        }
    }

}
