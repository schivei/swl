<?php

namespace swl\core;

use \BadMethodCallException,
    \Exception,
    \swl\core\exceptions\InvalidFileException;

/**
 * Description of Lexer
 *
 * @author schivei
 */
class Lexer
{

    /**
     * @var \SplFileObject
     */
    private $file;

    public function __construct(\SplFileObject $file = null, $content = null)
    {
        if (\is_null($file) && !\is_null($content))
        {
            $tmp  = sys_get_temp_dir();
            $tmp .= DIRECTORY_SEPARATOR . uniqid() . '.swl';
            file_put_contents($tmp, (string) $content);
            $temp = new \SplFileObject($tmp);
            $temp->fwrite($content, \strlen($content));
            $temp->rewind();
            $file = $temp;
        }

        $this->file = $file;
    }

    /**
     * @param string $file
     * @return \swl\core\Token[]
     * @throws Exception
     */
    public static function &run($file)
    {
        $d = new \swl\core\Lexer(new \SplFileObject($file));
        $a = &$d->analize();
        return $a;
    }

    /**
     * @return \swl\core\Token[]
     * @throws Exception
     */
    public function &analize()
    {
        $toks = [];

        if (!($this->file instanceof \SplFileObject))
        {
            throw new BadMethodCallException("Please, enter the file source code.");
        }

        if (\strtolower($this->file->getExtension()) !== 'swl')
        {
            throw new InvalidFileException("The file is not a SWL source code.",
                                           $this->file->getFilename());
        }

        $inString            = false;
        $inComment           = false;
        $initialCommentChars = null;
        $initialStringChar   = null;
        $sequence            = "";
        $line                = 1;
        $pos                 = -1;
        $iniPos              = 0;
        $lastChar            = null;
        $nextChar            = null;
        $currChar            = null;
        do
        {
            $currLine = new collections\StringIterator($this->file->current());

            do
            {
                $lastChar = $currChar;
                $currChar = $nextChar ? : $currLine->current();
                $nextChar = $currLine->getNextChar()? : "\n";

                if ($inString)
                {
                    $sequence .= $currChar;

                    if ((($initialStringChar === '"' && $currChar === '"') || ($initialStringChar ===
                            "'" && $currChar === "'")) && $lastChar !== '\\')
                    {
                        $r = new \swl\core\Token($initialStringChar === '"' ? 'T_ESCAPED_STRING'
                                            : 'T_UNESCAPED_STRING', $sequence,
                                                 $line, $iniPos,
                                                 $this->file->getFilename());

                        \array_push($toks, $r);

                        $sequence = "";
                        $inString = false;
                        $currLine->next();
                        continue;
                    }

                    $currLine->next();
                    continue;
                }

                if ($inComment)
                {
                    $sequence .= $currChar;

                    if (($initialCommentChars === "/*" && $lastChar === '*' && $currChar ===
                            '/') || ($initialCommentChars === '//' && ($nextChar ===
                            "\n")))
                    {
                        $r = new \swl\core\Token($initialCommentChars === "/*" ? 'T_MULTILINE_COMMENT'
                                            : 'T_SINGLELINE_COMMENT', $sequence,
                                                 $line, $iniPos,
                                                 $this->file->getFilename());

                        \array_push($toks, $r);

                        $sequence  = "";
                        $inComment = false;

                        if ($nextChar === "\n")
                        {
                            $line++;
                        }

                        $currLine->next();
                        continue;
                    }

                    if ($nextChar === "\n")
                    {
                        $line++;
                    }

                    $currLine->next();
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
                            $nextChar = $this->file->fgetc();
                        }

                        $finishingRegex = false;

                        $r = new \swl\core\Token('T_REGEXP', $sequence, $line,
                                                 $iniPos,
                                                 $this->file->getFilename());

                        \array_push($toks, $r);

                        $sequence = '';
                        $currLine->next();
                        continue;
                    }

                    $finishingRegex = false;

                    $sequence .= $currChar;
                    $currLine->next();
                    continue;
                }
                else if (\preg_match('/^(reg\/)/', $sequence))
                {
                    $sequence .= $currChar;
                    $currLine->next();
                    continue;
                }
                else if ($currChar === '/' && ($nextChar === '/' || $nextChar ===
                        '*'))
                {
                    $iniPos              = $currLine->key();
                    $inComment           = true;
                    $initialCommentChars = $currChar . $nextChar;
                    $sequence .= $currChar;
                    $currLine->next();
                    continue;
                }

                if ($currChar === '"' || $currChar === "'")
                {
                    $iniPos            = $currLine->key();
                    $inString          = true;
                    $initialStringChar = $currChar;
                    $sequence .= $currChar;
                    $currLine->next();
                    continue;
                }

                if (\preg_match('/^([ \n\r\t\f])/', $nextChar))
                {
                    $sequence .= $currChar;
                    if (\strlen($sequence) > 0)
                    {
                        $token = $this->_match($sequence);

                        $r = new \swl\core\Token($token, $sequence, $line,
                                                 $iniPos,
                                                 $this->file->getFilename());

                        \array_push($toks, $r);

                        $sequence = '';
                    }

                    $r = new \swl\core\Token('T_WHITESPACE', $nextChar, $line,
                                             $pos, $this->file->getFilename());

                    \array_push($toks, $r);

                    $line++;
                    $currLine->next();
                    continue;
                }

                $result = $this->_pairChar($currChar);
                if ($result !== false)
                {
                    if (\strlen($sequence) > 0)
                    {
                        $token = $this->_match($sequence);

                        $r = new \swl\core\Token($token, $sequence, $line,
                                                 $iniPos,
                                                 $this->file->getFilename());

                        \array_push($toks, $r);

                        $sequence = '';
                    }

                    $r = new \swl\core\Token($result, $currChar, $line, $pos,
                                             $this->file->getFilename());

                    \array_push($toks, $r);
                    $currLine->next();
                    continue;
                }

                if (\strlen($sequence) === 0) $iniPos = $currLine->key();

                $sequence .= $currChar;
                $currLine->next();
            }
            while ($currLine->valid());
            $currChar = $nextChar;
            $nextChar = null;
            $this->file->next();
        }
        while ($this->file->valid());

        return $toks;
    }

    protected function _match($sequence)
    {
        $toks = \swl\core\Tokens::allTerminals();
        $ret  = null;
        if (!$sequence || \preg_match('/^([ \n\r\t\f])/', $sequence))
                $ret  = 'T_WHITESPACE';
        else
                foreach ($toks as $pattern => $name)
            {
                if (\preg_match($pattern, $sequence)) $ret = $name;
                if ($ret) break;
            }

        return $ret;
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
        $d = new self(null, $content);
        $a = &$d->analize();
        return $a;
    }

}
