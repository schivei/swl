<?php

namespace swl\core\exceptions;

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
