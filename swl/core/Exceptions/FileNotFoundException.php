<?php

namespace swl\core\Exceptions;

/**
 * Description of FileNotFoundException
 *
 * @author schivei
 */
class FileNotFoundException extends InvalidFileException
{

    public function __construct($filename)
    {
        parent::__construct("File not found.", $filename);
    }

}
