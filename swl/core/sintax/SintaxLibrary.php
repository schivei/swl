<?php

namespace swl\core\sintax;

/**
 * Description of SintaxLibrary
 *
 * @author schivei
 */
class SintaxLibrary extends \swl\core\types\TypeObject implements ISintax
{
    public function __construct(\Iterator $tokens)
    {
        $this->value = $tokens;
    }

    public function getAParsedCommentedFile()
    {
        
    }

    public function getParsedFiles()
    {
        
    }
}
