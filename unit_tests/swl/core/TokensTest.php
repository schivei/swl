<?php

use \swl\core\Token,
    \swl\core\Tokens;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-03 at 21:15:54.
 */
class TokensTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * Generated from @assert ("a", 1, 0) == null.
     *
     * @covers \swl\core\Tokens::DoubleAnalize
     */
    public function testDoubleAnalize()
    {
        $this->assertEquals(
                null, Tokens::DoubleAnalize("a", 1, 0, "test.swl"),
                                            "Test the fail double analizer."
        );
    }

    /**
     * Generated from @assert ("::", 1, 0) == new Token("T_STATIC_CALL", "::", 1, 0).
     *
     * @covers \swl\core\Tokens::DoubleAnalize
     */
    public function testDoubleAnalize2()
    {
        $this->assertEquals(
                new Token("T_STATIC_CALL", "::", 1, 0, "test.swl"),
                          Tokens::DoubleAnalize("::", 1, 0, "test.swl"),
                                                "Test the succes double analizer."
        );
    }

    /**
     * Generated from @assert ("T_STATIC_CALL") == '/^(::)$/'.
     *
     * @covers \swl\core\Tokens::GetPattern
     */
    public function testGetPattern()
    {
        $this->assertEquals(
                '/^(::)$/', Tokens::GetPattern("T_STATIC_CALL"),
                                               "Test the special terminals get pattern"
        );
    }

    /**
     * Generated from @assert ("T_CONTROLLER") == '/^(controller)$/'.
     *
     * @covers \swl\core\Tokens::GetPattern
     */
    public function testGetPattern2()
    {
        $this->assertEquals(
                '/^(controller)$/', Tokens::GetPattern("T_CONTROLLER"),
                                                       "Test the terminals get pattern"
        );
    }

    /**
     * Generated from @assert ("T_EOL") == '/^([;])$/'.
     *
     * @covers \swl\core\Tokens::GetPattern
     */
    public function testGetPattern3()
    {
        $this->assertEquals(
                '/^(;)$/', Tokens::GetPattern("T_EOL"),
                                              "Test the simple terminals get pattern"
        );
    }

    /**
     * Generated from @assert ("T_INVALID") == null.
     *
     * @covers \swl\core\Tokens::GetPattern
     */
    public function testGetPattern4()
    {
        $this->assertEquals(
                null, Tokens::GetPattern("T_INVALID"),
                                         "Test the invalid token get pattern"
        );
    }

}
