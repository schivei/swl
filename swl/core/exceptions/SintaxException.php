<?php

namespace swl\core\exceptions;

/**
 * Description of SintaxException
 *
 * @author schivei
 */
class SintaxException extends \ErrorException
{

    /**
     * @var \swl\core\Token
     */
    private $token;
    private $sequence;
    private $column;

    public function __construct($message, \swl\core\Token &$token)
    {
        parent::__construct($message, 0xf03, E_COMPILE_ERROR, $token->getFile(),
                            $token->getLine(), null);
        $this->column   = $token->getPosition();
        $this->sequence = $token->getPosition();
        $this->token    = &$token;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function &getToken()
    {
        return $this->token;
    }

}
