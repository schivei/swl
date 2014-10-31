<?php

namespace swl\core;

/**
 * Description of Token
 *
 * @author schivei
 */
class Token implements Serializable
{

    private $token;
    private $sequence;
    private $line;
    private $initialPosition;

    public function __construct($token, $sequence, $line, $initialPosition)
    {
        $this->token           = $token;
        $this->sequence        = $sequence;
        $this->line            = $line;
        $this->initialPosition = $initialPosition;
    }

    public function getType()
    {
        return $this->token;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getPosition()
    {
        return $this->initialPosition;
    }

    public function serialize()
    {
        return serialize([
            'token'           => $this->token,
            'sequence'        => $this->sequence,
            'line'            => $this->line,
            'initialPosition' => $this->initialPosition
        ]);
    }

    public function unserialize($serialized)
    {
        $serialized            = unserialize($serialized);
        $this->token           = $serialized['token'];
        $this->sequence        = $serialized['sequence'];
        $this->line            = $serialized['line'];
        $this->initialPosition = $serialized['initialPosition'];
    }

}
