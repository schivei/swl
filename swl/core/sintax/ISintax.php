<?php

namespace swl\core\sintax;

use \Iterator;

/**
 *
 * @author schivei
 */
interface ISintax
{

    function __construct(Iterator $tokens);

    function getParsedFiles();

    function getAParsedCommentedFile();
}
