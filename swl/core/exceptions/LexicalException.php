<?php

namespace swl\core\exceptions;

/**
 * Description of LexicalException
 *
 * @author schivei
 */
class LexicalException extends \ErrorException
{

    private $sequence;
    private $column;

    public function __construct($message, $filename, $lineno, $colno, $sequence)
    {
        parent::__construct($message, 0xf01, E_COMPILE_ERROR, $filename,
                            $lineno, null);
        $this->column   = $colno;
        $this->sequence = $sequence;
    }

    /**
     * Gets the Exception line column
     * @return int the Exception line column as integer.
     */
    public function getPosition()
    {
        return $this->column;
    }

    /**
     * Gets the Exception sequence
     * @return string the Exception sequence as string.
     */
    public function getSequence()
    {
        return $this->sequence;
    }

}
