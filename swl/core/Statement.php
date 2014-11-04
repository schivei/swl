<?php

namespace swl\core;

/**
 * Description of Statement
 *
 * @author schivei
 */
class Statement
{

    /**
     * @var \Iterator
     */
    private $childs;

    /**
     * @var Token
     */
    private $parent;

    public function __construct(Token $parent, \Iterator $childs)
    {
        $this->parent = $parent;
        $this->childs = $childs;
    }

    public static function &Prepare(\Iterator $tokens)
    {
        
    }

}
