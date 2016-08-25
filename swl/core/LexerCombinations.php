<?php

namespace swl\core;

/**
 * Description of LexerCombinations
 *
 * @author schivei
 */
class LexerCombinations
{

    /**
     * @var \swl\core\Lexer
     */
    private $lex;

    /**
     * @var \swl\core\Token
     */
    private $tokens;

    /**
     * @var \swl\core\Token
     */
    private $combinedTokens;

    public function __construct(\swl\core\Lexer $lex)
    {
        $this->lex = $lex;
    }

    /**
     * @return \swl\core\Token[]
     */
    public function &getTokens()
    {
        $this->tokens = $this->lex->analize();

        $this->combinedTokens = $this->analize();

        return $this->combinedTokens;
    }

    public function &getTokensWithoutWhitespaces()
    {
        $toks = $this->getTokens();

        /* @var $token \swl\core\Token */
        foreach ($toks as &$token)
        {
            if ($token->getType() === 'T_WHITESPACE') continue;

            yield $token;
        }
    }

    /**
     * @return \swl\core\Token[]
     */
    private function &analize()
    {
        /* @var $last\swl\core\Token Token */
        $lastToken = null;

        /* @var $token \swl\core\Token */
        foreach ($this->tokens as &$token)
        {
            if (in_array($token->getType(), \swl\core\Tokens::$simpleTerminal))
            {
                if ($lastToken)
                {
                    $tok = \swl\core\Tokens::doubleAnalize($lastToken->getSequence() . $token->getSequence(),
                                                           $lastToken->getLine(),
                                                           $lastToken->getPosition(),
                                                           $lastToken->getFile());
                    if ($tok !== null)
                    {
                        yield $tok;

                        $lastToken = null;
                        continue;
                    }

                    yield $lastToken;
                }

                $lastToken = null;

                switch ($token->getType())
                {
                    case 'T_DOUBLECOMMA':
                    case 'T_MINOR':
                    case 'T_MAJOR':
                    case 'T_BITWISE_AND':
                    case 'T_BITWISE_OR':
                    case 'T_THIS_OBJECT':
                    case 'T_EQUAL':
                    case 'T_PLUS':
                    case 'T_MINUS':
                    case 'T_MOD':
                    case 'T_NEGATE':
                    case 'T_MULTIPLY':
                        $lastToken = $token;
                        break;

                    default:
                        $lastToken = null;
                        break;
                }

                if ($lastToken) continue;
            }

            if ($lastToken)
            {
                yield $lastToken;
                $lastToken = null;
            }

            yield $token;
        }
    }

}
