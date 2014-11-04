<?php

use \PHPUnit_Framework_TestCase,
    \swl\core\Token;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-04 at 01:13:41.
 */
class TokenTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Token
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Token("T_IDENTIFIER", "non", 3, 21, "test.swl");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::getType
     */
    public function testGetType()
    {
        $this->assertEquals(
                'T_IDENTIFIER', $this->object->getType()
        );
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::getSequence
     */
    public function testGetSequence()
    {
        $this->assertEquals(
                'non', $this->object->getSequence()
        );
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::getLine
     */
    public function testGetLine()
    {
        $this->assertEquals(
                3, $this->object->getLine()
        );
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::getPosition
     */
    public
            function testGetPosition()
    {
        $this->assertEquals(
                21, $this->object->getPosition()
        );
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::setPosition
     */
    public function

    testSetPosition()
    {
        $this->object->setPosition(54);

        $this->assertEquals(
                54, $this->object->getPosition()
        );

        $this->object->setPosition(21);
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::getFile
     */
    public function testGetFile()
    {
        $this->assertEquals(
                'test.swl', $this->object->getFile()
        );
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::serialize
     */
    public function testSerialize()
    {
        $serial = serialize($this->object);

        $this->assertEquals(
                'C:14:"swl\core\Token":106:{a:4:{s:5:"token";s:12:"T_IDENTIFIER"'
                . ';s:8:"sequence";s:3:"non";s:4:"line";i:3;s:15:"initialPosition"'
                . ';i:21;}}', $serial
        );
    }

    /**
     * Generated from @assert () == 'T_INVALID'.
     *
     * @covers swl\core\Token::unserialize
     */
    public function testUnserialize()
    {
        $serialized = serialize($this->object);

        $this->object->setPosition(101);

        $this->object->unserialize($serialized);

        $this->assertEquals(
                21, $this->object->getPosition()
        );
    }

    /**
     * Generated from @assert (0, 0) == true.
     *
     * @covers swl\core\Token::test
     */
    public function testTest()
    {
        $this->assertTrue($this->object->test('T_WHITESPACE', ' '));
    }

    /**
     * Generated from @assert (0, 0) == true.
     *
     * @covers swl\core\Token::test
     */
    public function testTestException()
    {
        $this->setExpectedException(\InvalidArgumentException::class,
                                    "The sequence is not a 'T_WHITESPACE' token.",
                                    0);
        $this->object->test('T_WHITESPACE', 'a');
    }

    /**
     * Generated from @assert (0, 0) == true.
     *
     * @covers swl\core\Token::test
     */
    public function testTestException2()
    {
        $this->setExpectedException(\InvalidArgumentException::class,
                                    "The token 'T_INVALID' is invalid.", 0);
        $this->object->test('T_INVALID', 'a');
    }

}
