<?php

namespace swl\core\exceptions;

/**
 * Description of InvalidFileException
 *
 * @author schivei
 */
class InvalidFileException extends \Exception
{

    public function __construct($message, $filename)
    {
        parent::__construct($message, 0xf02, null);
        $this->file = $filename;
    }

}
