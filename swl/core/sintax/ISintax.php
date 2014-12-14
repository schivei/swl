<?php

namespace swl\core\sintax;

use \swl\core\LexerCombinations;

/**
 *
 * @author schivei
 */
interface ISintax
{

    function __construct(LexerCombinations $lex);

    function analize();

    function getParsedFiles();

    function getAParsedCommentedFile();

    function checkOpenCloseStatements();
}
