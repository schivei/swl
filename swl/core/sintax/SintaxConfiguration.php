<?php

namespace swl\core\sintax;
use \Iterator;

/**
 * Description of SintaxConfiguration
 *
 * @author schivei
 */
class SintaxConfiguration extends \swl\core\types\TypeObject implements ISintax
{
    public function getAParsedCommentedFile()
    {
        
    }

    public function getParsedFiles()
    {
        
    }

    public function __construct(Iterator $tokens)
    {
        $this->value = $tokens;
    }
}
