<?php

use \swl\core\types\Object;

/**
 * Description of Object
 *
 * @author Elton Schivei Costa <costa@elton.schivei.nom.br>
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Object::__construct
     */
    public function test__construct()
    {
        $obj = new Object();
        $this->assertNotNull($obj, "Testing object constructor.");
    }

    /**
     * @covers Object::__construct
     * @covers Object::__toString()
     */
    public function test__toString()
    {
        $obj = new Object();
        $this->assertEquals(Object::class, $obj->__toString(),
                            "Testing to string object method.");
        $this->assertEquals(Object::class, (string) $obj,
                            "Testing object to string.");
    }

}
