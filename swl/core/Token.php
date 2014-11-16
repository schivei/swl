<?php

namespace swl\core;

use \InvalidArgumentException,
    \Serializable;

/**
 * Description of Token
 *
 * @author schivei
 */
class Token implements Serializable
{

    private $file;
    private $token;
    private $sequence;
    private $line;
    private $initialPosition;

    public function __construct($token, $sequence, $line, $initialPosition,
                                $file)
    {
        $this->test($token, $sequence);

        $this->token           = $token;
        $this->sequence        = $sequence;
        $this->line            = $line;
        $this->initialPosition = $initialPosition;
        $this->file            = $file;
    }

    /**
     * @return string
     * @assert () == 'T_INVALID'
     */
    public function getType()
    {
        return $this->token;
    }

    /**
     * @return string
     * @assert () == 'T_INVALID'
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return int
     * @assert () == 'T_INVALID'
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return int
     * @assert () == 'T_INVALID'
     */
    public function getPosition()
    {
        return $this->initialPosition;
    }

    /**
     * @return void
     * @assert () == 'T_INVALID'
     */
    public function setPosition($ini)
    {
        $this->initialPosition = $ini;
    }

    /**
     * @return string
     * @assert () == 'T_INVALID'
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     * @assert () == 'T_INVALID'
     */
    public function serialize()
    {
        return \serialize([
            'token'           => $this->token,
            'sequence'        => $this->sequence,
            'line'            => $this->line,
            'initialPosition' => $this->initialPosition
        ]);
    }

    /**
     * @return void
     * @assert () == 'T_INVALID'
     */
    public function unserialize($serialized)
    {
        $u                     = \unserialize($serialized);
        $unserialized          = !\is_object($u) ? (object) $u : $u;
        $this->token           = $unserialized->token;
        $this->sequence        = $unserialized->sequence;
        $this->line            = $unserialized->line;
        $this->initialPosition = $unserialized->initialPosition;
    }

    /**
     * @param type $token
     * @param type $sequence
     * @throws InvalidArgumentException
     * @assert (0, 0) == true
     */
    public function test($token, $sequence)
    {
        $tok = \in_array($token, \swl\core\Tokens::$_simpleTerminal)
                || \in_array($token, \swl\core\Tokens::$_specialTerminals)
                || \in_array($token, \swl\core\Tokens::$_terminals);

        if (!$tok)
                throw new InvalidArgumentException("The token '{$token}' is invalid.");

        $pattern = \swl\core\Tokens::GetPattern($token);

        if (null === $pattern)
                throw new InvalidArgumentException("The sequence is not a '{$token}' token.");

        if (!\preg_match($pattern, $sequence))
                throw new InvalidArgumentException("The sequence is not a '{$token}::{$sequence}' token.");

        return true;
    }

}
