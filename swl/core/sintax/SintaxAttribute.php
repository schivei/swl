<?php

namespace swl\core\sintax;

/**
 * Description of SintaxAttribute
 *
 * @author schivei
 */
class SintaxAttribute extends \swl\core\types\TypeObject implements ISintax
{

    public function getAParsedCommentedFile()
    {

    }

    public function getParsedFiles()
    {

    }

    public function __construct(\Iterator $tokens)
    {
        $this->value = $tokens;
    }

}
