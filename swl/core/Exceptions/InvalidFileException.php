<?php

namespace swl\core\Exceptions;

/**
 * Description of InvalidFileException
 *
 * @author schivei
 */
class InvalidFileException extends \Exception
{

    private $filename;

    public function __construct($message, $filename)
    {
        parent::__construct($message, 0xf02, null);
        $this->filename = $filename;
    }

    public function getFile()
    {
        return $this->filename;
    }

}
