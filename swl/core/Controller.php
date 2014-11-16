<?php

namespace swl\core;

use \swl\core\types\Object,
    \swl\core\types\String,
    \swl\core\View;

/**
 * Description of Controller
 *
 * @author schivei
 */
abstract class Controller extends Object
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

    final protected function setView(String $viewName)
    {
        $this->_view = new View($viewName);
    }

}
