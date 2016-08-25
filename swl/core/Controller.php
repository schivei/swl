<?php

namespace swl\core;

use \swl\core\types\TypeObject,
    \swl\core\types\TypeString,
    \swl\core\View;

/**
 * Description of Controller
 *
 * @author schivei
 */
abstract class Controller extends TypeObject
{

    /**
     * @var View
     */
    private $_view;

    public function __construct()
    {
        parent::__construct();

        $this->_view = new View();
    }

    final protected function setView(TypeString $viewName)
    {
        $this->_view = new View($viewName);
    }

}
