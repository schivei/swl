<?php

namespace swl\core\sintax;

/**
 * Description of SintaxModule
 *
 * @author schivei
 */
class SintaxModule extends \swl\core\types\TypeObject implements ISintax
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
