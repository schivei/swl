<?php

use \swl\core\collections\Linq,
    \swl\core\exceptions\InvalidFileException,
    \swl\core\Lexer,
    \swl\core\LexerCombinations,
    \swl\core\Token;

/**
 * Description of LexerTest
 *
 * @author schivei
 */
class LexerTest extends \PHPUnit_Framework_TestCase
{

    private $expecteds = [];
    private $toks      = ['a', 'aaa', '_aAa', '_aA9', '_model', 'model'];

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->expecteds);
        unset($this->toks);
    }

    protected function setUp()
    {
        $i = 0;
        for (; $i < 5; $i++)
        {
            $this->expecteds[$this->toks[$i]] = serialize(new Token('T_IDENTIFIER',
                                                                    $this->toks[$i],
                                                                    1, 0,
                                                                    'test.swl'));
        }

        $this->expecteds[$this->toks[$i]] = serialize(new Token('T_MODEL',
                                                                $this->toks[$i],
                                                                1, 0, 'test.swl'));
    }

    private function &lexInstances($str)
    {
        $instances = &Lexer::runString($str);
        $this->assertTrue(\is_array($instances),
                                    'Test if Lexer return a Generator instance.');

        $linq = new Linq($instances);
        return $linq;
    }

    private function &lexFileInstances($file)
    {
        $instances = &Lexer::run(\INCPATH . $file);
        $this->assertTrue(\is_array($instances),
                                    'Test if Lexer file return a Generator instance.');
        $linq      = new Linq($instances);
        return $linq;
    }

    private function &lexInstance(Linq &$from)
    {
        $stack = &$from->First();
        return $stack;
    }

    /**
     * @test
     * @covers \swl\core\Lexer::runString
     * @covers \swl\core\Lexer::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::_pairChar
     * @covers \swl\core\Lexer::_match
     * @covers \swl\core\Token::serialize
     * @covers \swl\core\Token::unserialize
     */
    public function testAnalizeForFiveIdentifiersAndOneLeast()
    {
        for ($i = 0; $i < 6; $i++)
        {
            $tok = $this->toks[$i];

            $expected = $this->expecteds[$tok];

            $from = &$this->lexInstances($tok);

            /* @var $instance Token */
            $instance = &$this->lexInstance($from);

            $arr = $from->ToArray();

            $this->assertInstanceOf(Linq::class, $from,
                                    'Test the if token iterator is a Linq class.');

            $this->assertCount(2, $arr, 'Test the number of tokens.');

            $this->assertInstanceOf("\swl\core\Token", $instance,
                                    'Test the last token type.');

            $this->assertEquals($expected, serialize($instance),
                                                     'Test the first token content.');
        }
    }

    /**
     * @covers \swl\core\Lexer::runString
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::_pairChar
     * @covers \swl\core\Lexer::_match
     */
    public function testSWLStringCodeAndCountTokens()
    {
        $content = "controller MyController {
    action '/' -> index {
        @view.data = \"Test\";
    }
}";

        $from = &$this->lexInstances($content);

        $this->assertCount(75, $from->ToArray(), "Test a SWL Controller code.");
    }

    /**
     * @covers \swl\core\Lexer::run
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Lexer::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::_pairChar
     * @covers \swl\core\Lexer::_match
     * @covers \swl\core\LexerCombinations::__construct
     * @covers \swl\core\LexerCombinations::GetTokens
     * @covers \swl\core\LexerCombinations::analize
     */
    public function testSWLControllerFileAndCountTokens()
    {
        $from = &$this->lexFileInstances('controller.swl');

        $arr = $from->ToArray();

        $this->assertCount(161, $arr, "Test a SWL file Controller code.");

        $comb = new LexerCombinations(new Lexer(new \SplFileObject(\INCPATH . 'controller.swl')));

        $tokens = $comb->GetTokens();

        $this->assertArrayHasKey(0, $tokens,
                                 'Test if Lexer Combinations return a Generator instance.');
    }

    /**
     * @covers \swl\core\Lexer::run
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::_pairChar
     * @covers \swl\core\Lexer::_match
     * @covers \swl\core\LexerCombinations::GetTokens
     * @covers \swl\core\LexerCombinations::analize
     */
    public function testAnalizerEmptyFileAndContent()
    {
        $this->setExpectedException(\BadMethodCallException::class,
                                    "Please, enter the file source code.");

        $lex = new Lexer(null, null);

        $lex->analize();
    }

    /**
     * @covers \swl\core\Lexer::run
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::_pairChar
     * @covers \swl\core\Lexer::_match
     * @covers \swl\core\LexerCombinations::GetTokens
     * @covers \swl\core\LexerCombinations::analize
     */
    public function testAnalizerinvalidFile()
    {
        $this->setExpectedException(InvalidFileException::class,
                                    "The file is not a SWL source code.");

        $lex = new Lexer(new \SplFileObject(__FILE__), null);

        $lex->analize();
    }

}
