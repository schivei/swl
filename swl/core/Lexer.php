<?php

namespace swl\core;

use \BadMethodCallException,
    \Exception,
    \swl\core\exceptions\FileNotFoundException,
    \swl\core\exceptions\InvalidFileException,
    \swl\core\exceptions\LexicalException;

/**
 * Description of Lexer
 *
 * @author schivei
 */
class Lexer
{

    private $file;
    private $content;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @param string $file
     * @return \swl\core\Token[]
     * @throws Exception
     */
    public static function &run($file)
    {
        $d = new \swl\core\Lexer($file);
        $a = &$d->analize();
        return $a;
    }

    /**
     * @return \swl\core\Token[]
     * @throws Exception
     */
    public function &analize()
    {
        if (!$this->file && !$this->content)
        {
            throw new BadMethodCallException("Please, enter the file source code.");
        }
        else if (!\file_exists($this->file) && !$this->content)
        {
            throw new FileNotFoundException($this->file);
        }
        else if (\is_dir($this->file) && !$this->content)
        {
            throw new InvalidFileException("The file is a directory.",
                                           $this->file);
        }
        else if (!\preg_match('/(\.swl)$/i', $this->file) && !$this->content)
        {
            throw new InvalidFileException("The file is not a SWL source code.",
                                           $this->file);
        }

        $source = !(!$this->file) ? \file_get_contents($this->file) : $this->content;
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
                    $r = new \swl\core\Token($initialStringChar === '"' ? 'T_ESCAPED_STRING'
                                        : 'T_UNESCAPED_STRING', $sequence,
                                             $line, $iniPos, $this->file);

                    yield $r;

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
                    $r = new \swl\core\Token($initialCommentChars === "/*" ? 'T_MULTILINE_COMMENT'
                                        : 'T_SINGLELINE_COMMENT', $sequence,
                                             $line, $iniPos, $this->file);

                    yield $r;

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
                if ($lastChar != '\\' && $currChar === '/' && $sequence != 'reg')
                {
                    $sequence .= $currChar;
                    if ($nextChar === 'i')
                    {
                        $sequence .= $nextChar;
                        $i++;
                    }

                    $finishingRegex = false;

                    $r = new \swl\core\Token('T_REGEXP', $sequence, $line,
                                             $iniPos, $this->file);

                    yield $r;

                    $sequence = '';
                    continue;
                }

                $finishingRegex = false;

                $sequence .= $currChar;
                continue;
            }
            else if (\preg_match('/^(reg\/)/', $sequence))
            {
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
                        throw new LexicalException("Unable to identify the current sequence.",
                                                   $this->file, $line, $pos,
                                                   $sequence);
                    }

                    $r = new \swl\core\Token($token, $sequence, $line, $iniPos,
                                             $this->file);

                    yield $r;

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
                        throw new LexicalException("Unable to identify the current sequence.",
                                                   $this->file, $line, $pos,
                                                   $sequence);
                    }

                    $r = new \swl\core\Token($token, $sequence, $line, $iniPos,
                                             $this->file);

                    yield $r;

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
                        throw new LexicalException("Unable to identify the current sequence.",
                                                   $this->file, $line, $pos,
                                                   $sequence);
                    }

                    $r = new \swl\core\Token($token, $sequence, $line, $iniPos,
                                             $this->file);

                    yield $r;

                    $sequence = '';
                }

                if ($nextChar !== null)
                {
                    $r = new \swl\core\Token('T_WHITESPACE', $currChar, $line,
                                             $pos, $this->file);

                    yield $r;

                    if ($currChar === "\n")
                    {
                        $pos = -1;
                        $line++;
                    }
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
                        throw new LexicalException("Unable to identify the current sequence.",
                                                   $this->file, $line, $pos,
                                                   $sequence);
                    }

                    $r = new \swl\core\Token($token, $sequence, $line, $iniPos,
                                             $this->file);

                    yield $r;

                    $sequence = '';
                }

                $r = new \swl\core\Token($result, $currChar, $line, $pos,
                                         $this->file);

                yield $r;

                continue;
            }

            if (\strlen($sequence) === 0) $iniPos = $pos;

            $sequence .= $currChar;
        }
    }

    protected function _match($sequence)
    {
        if (!$sequence) return 'T_WHITESPACE';

        foreach (\swl\core\Tokens::$_terminals as $pattern => $name)
        {
            $matches = null;
            if (\preg_match_all($pattern, $sequence, $matches)) return $name;
        }

        return false;
    }

    protected function _pairChar($char)
    {
        if (!$char) return 'T_WHITESPACE';

        foreach (\swl\core\Tokens::$_simpleTerminal as $pattern => $name)
        {
            $matches = null;
            if (\preg_match_all($pattern, $char, $matches)) return $name;
        }

        return false;
    }

    /**
     * @param string $content
     * @return \swl\core\Token[]
     * @throws Exception
     */
    public static function &runString($content)
    {
        $d          = new self(null);
        $d->content = $content;
        $a          = &$d->analize();
        return $a;
    }

}
