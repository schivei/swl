<?php

namespace swl\core;

/**
 * Description of Lexer
 *
 * @author schivei
 */
class Lexer
{

    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @param string $file
     * @return Generator
     * @throws Exception
     */
    public static function run($file)
    {
        $d = new Lexer($file);
        return $d->analize();
    }

    /**
     * @return Generator
     * @throws Exception
     */
    public function analize()
    {
        if (!\file_exists($this->file))
        {
            throw new Exceptions\FileNotFoundException($this->file);
        }
        else if (\is_dir($this->file))
        {
            throw new Exceptions\InvalidFileException("The file is a directory.",
                                                      $this->file);
        }
        else if (!\preg_match('/(\.swl)$/i', $this->file))
        {
            throw new Exceptions\InvalidFileException("The file is not a SWL source code.",
                                                      $this->file);
        }

        $source = \file_get_contents($this->file);
        $source = \str_replace("\r", "\n", \str_replace("\r\n", "\n", $source)) . "\n";
        $chars  = \str_split($source);

        $inString            = false;
        $inComment           = false;
        $initialCommentChars = null;
        $initialStringChar   = null;
        $sequence            = "";
        $t                   = \count($chars);
        $line                = 1;
        $pos                 = -1;
        $iniPos              = 0;
        for ($i = 0; $i < $t; $i ++)
        {
            $lastChar = $i > 0 ? $chars[$i - 1] : null;
            $nextChar = $i < $t - 1 ? $chars[$i + 1] : null;
            $currChar = $chars[$i];

            $pos++;

            if ($inString)
            {
                $sequence .= $currChar;

                if ((($initialStringChar === '"' && $currChar === '"') || ($initialStringChar ===
                        "'" && $currChar === "'")) && $lastChar !== '\\')
                {
                    yield new Token($initialStringChar === '"' ? 'T_ESCAPED_STRING'
                                        : 'T_UNESCAPED_STRING', $sequence,
                                    $line, $iniPos);
                    $sequence = "";
                    $inString = false;
                    continue;
                }

                if ($currChar === "\n")
                {
                    $pos = -1;
                    $line++;
                }

                continue;
            }

            if ($inComment)
            {
                $sequence .= $currChar;

                if (($initialCommentChars === "/*" && $lastChar === '*' && $currChar ===
                        '/') || ($initialCommentChars === '//' && $currChar === "\n"))
                {
                    yield new Token($initialCommentChars === "/*" ? 'T_MULTILINE_COMMENT'
                                        : 'T_SINGLELINE_COMMENT', $sequence,
                                    $line, $iniPos);
                    $sequence  = "";
                    $inComment = false;

                    if ($currChar === "\n")
                    {
                        $pos = -1;
                        $line++;
                    }

                    continue;
                }

                if ($currChar === "\n")
                {
                    $pos = -1;
                    $line++;
                }

                continue;
            }

            if (\preg_match('/^(reg)/', $sequence) && $nextChar !== '/' && $currChar ===
                    '/')
            {
                if ($lastChar != '\\' && $currChar === '/')
                {
                    yield new Token('T_REGEXP', $sequence, $line, $iniPos);
                    $sequence = '';
                    continue;
                }

                $sequence .= $currChar;
                continue;
            }
            else if ($currChar === '/' && ($nextChar === '/' || $nextChar === '*'))
            {
                if (\strlen($sequence) > 0)
                {
                    $token = static::_match($sequence, $line, 0);

                    if ($token === false)
                    {
                        throw new Exceptions\LexicalException("Unable to identify the current sequence.",
                                                              $this->file,
                                                              $line, $pos,
                                                              $sequence);
                    }

                    yield new Token($token, $sequence, $line, $iniPos);
                    $sequence = '';
                }

                $iniPos              = $pos;
                $inComment           = true;
                $initialCommentChars = $currChar . $nextChar;
                $sequence .= $currChar;
                continue;
            }

            if ($currChar === '"' || $currChar === "'")
            {
                if (\strlen($sequence) > 0)
                {
                    $token = static::_match($sequence, $line, 0);

                    if ($token === false)
                    {
                        throw new Exceptions\LexicalException("Unable to identify the current sequence.",
                                                              $this->file,
                                                              $line, $pos,
                                                              $sequence);
                    }

                    yield new Token($token, $sequence, $line, $iniPos);
                    $sequence = '';
                }

                $iniPos            = $pos;
                $inString          = true;
                $sequence .= $currChar;
                $initialStringChar = $currChar;
                continue;
            }

            if (\preg_match('/^([ \n\t\f])/', $currChar))
            {
                if (\strlen($sequence) > 0)
                {
                    $token = $this->_match($sequence, $line, 0);

                    if ($token === false)
                    {
                        throw new Exceptions\LexicalException("Unable to identify the current sequence.",
                                                              $this->file,
                                                              $line, $pos,
                                                              $sequence);
                    }

                    yield new Token($token, $sequence, $line, $iniPos);
                    $sequence = '';
                }

                yield new Token('T_WHITESPACE', $currChar, $line, $pos);

                if ($currChar === "\n")
                {
                    $pos = -1;
                    $line++;
                }

                continue;
            }

            $result = $this->_pairChar($currChar);
            if ($result !== false)
            {
                if (\strlen($sequence) > 0)
                {
                    $token = $this->_match($sequence, $line, 0);

                    if ($token === false)
                    {
                        throw new Exceptions\LexicalException("Unable to identify the current sequence.",
                                                              $this->file,
                                                              $line, $pos,
                                                              $sequence);
                    }

                    yield new Token($token, $sequence, $line, $iniPos);
                    $sequence = '';
                }

                yield new Token($result, $currChar, $line, $pos);
                continue;
            }

            if (\strlen($sequence) === 0) $iniPos = $pos;

            $sequence .= $currChar;
        }
    }

    protected function _match($sequence)
    {
        if (!$sequence) return 'T_WHITESPACE';

        foreach (Tokens::$_terminals as $pattern => $name)
        {
            $matches = null;
            if (\preg_match_all($pattern, $sequence, $matches)) return $name;
        }

        return false;
    }

    protected function _pairChar($char)
    {
        if (!$char) return 'T_WHITESPACE';

        foreach (Tokens::$_simpleTerminal as $pattern => $name)
        {
            $matches = null;
            if (\preg_match_all($pattern, $char, $matches)) return $name;
        }

        return false;
    }

}
