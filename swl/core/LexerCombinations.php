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

    /**
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\LexerCombinations::analize
     * @param \swl\core\Lexer $lex
     */
    public function __construct(\swl\core\Lexer $lex)
    {
        $this->lex = $lex;

        $this->tokens = &$this->lex->analize();

        $this->combinedTokens = [];
    }

    /**
     * @return \swl\core\Token[]
     */
    public function GetTokens()
    {
        if (count($this->combinedTokens) > 0) return $this->combinedTokens;

        /* @var $last\swl\core\Token Token */
        $lastToken = null;

        /* @var $token \swl\core\Token */
        foreach ($this->tokens as $token)
        {
            if (in_array($token->getType(), \swl\core\Tokens::$_simpleTerminal))
            {
                if ($lastToken)
                {
                    $tok = \swl\core\Tokens::DoubleAnalize($lastToken->getSequence() . $token->getSequence(),
                                                           $lastToken->getLine(),
                                                           $lastToken->getPosition(),
                                                           $lastToken->getFile());
                    if ($tok)
                    {
                        \array_push($this->combinedTokens, $tok);

                        $lastToken = null;
                        continue;
                    }

                    \array_push($this->combinedTokens, $lastToken);
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
                \array_push($this->combinedTokens, $lastToken);
                $lastToken = null;
            }

            \array_push($this->combinedTokens, $token);
        }

        return $this->combinedTokens;
    }

}
