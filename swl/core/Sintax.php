<?php

namespace swl\core;

/**
 * Description of Sintax
 *
 * @author schivei
 */
class Sintax
{

    /**
     * @var Lexer[]
     */
    private $lexers;

    public function __construct(\Generator $lexers)
    {
        $this->lexers = $lexers;
    }

    public function analize()
    {
        foreach ($this->lexers as $lexer)
        {
            /* @var $tokens Token */
            $tokens = $lexer->analize();
        }
    }

}
