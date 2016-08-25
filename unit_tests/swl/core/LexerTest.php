<?php

namespace swl\core;

use \swl\core\collections\Linq,
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
        $instances = Lexer::runString($str);
        $this->assertInstanceOf(\Generator::class, $instances,
                                'Test if Lexer return a Generator instance.');
        $linq      = new Linq($instances);
        return $linq;
    }

    private function &lexFileInstances($file)
    {
        $instances = Lexer::run(\INCPATH . $file);
        $this->assertInstanceOf(\Generator::class, $instances,
                                'Test if Lexer file return a Generator instance.');
        $linq      = new Linq($instances);
        return $linq;
    }

    private function &lexInstance(Linq &$from)
    {
        $stack = $from->First();
        return $stack;
    }

    private function &lexLastInstance(Linq &$from)
    {
        $stack = $from->Last();
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
     * @covers \swl\core\Lexer::pairChar
     * @covers \swl\core\Lexer::match
     * @covers \swl\core\Token::serialize
     * @covers \swl\core\Token::unserialize
     */
    public function testAnalizeForFiveIdentifiersAndOneLeast()
    {
        for ($i = 0; $i < 6; $i++)
        {
            $tok = $this->toks[$i];

            $expected = $this->expecteds[$tok];

            $from = $this->lexInstances($tok);

            /* @var $instance Token */
            $instance = $this->lexInstance($from);

            /* @var $linstance Token */
            $linstance = $this->lexLastInstance($from);

            $arr = $from->ToArray();

            $this->assertInstanceOf(Linq::class, $from,
                                    'Test the if token iterator is a Linq class.');
            $this->assertCount(1, $arr, 'Test the number of tokens.');
            $this->assertEquals($instance, $linstance,
                                'Test the last token are equals first the token.');
            $this->assertInstanceOf("\swl\core\Token", $instance,
                                    'Test the last token type.');
            $this->assertInstanceOf("\swl\core\Token", $linstance,
                                    'Test the last token type.');

            $this->assertEquals($expected, serialize($instance),
                                                     'Test the first token content.');
            $this->assertEquals($expected, serialize($linstance),
                                                     'Test the last token content.');
        }
    }

    /**
     * @covers \swl\core\Lexer::runString
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::pairChar
     * @covers \swl\core\Lexer::match
     * @covers \swl\core\LexerCombinations::GetTokensWithoutWhitespaces
     * @covers \swl\core\LexerCombinations::__construct
     * @covers \swl\core\LexerCombinations::analize
     */
    public function testSWLStringCodeAndCountTokens()
    {
        $content = "controller MyController {
    action '/' -> index {
        !(StaticClass::count() * 2 - 3 & 7 | 9 + @view.total);
        @view.data = \"Test\";
    }
}";

        $from = $this->lexInstances($content);

        $this->assertCount(88, $from->ToArray(), "Test a SWL Controller code.");

        $comb = new LexerCombinations(new Lexer(null, $content));

        $ntokens = $comb->getTokensWithoutWhitespaces();
        
        \file_put_contents('jj.json', \serialize($from->ToArray()));

        $from = new Linq($ntokens);
        
        file_put_contents('j.json', \serialize($from->ToArray()));

        $this->assertCount(39, $from->ToArray(),
                           "Test a SWL Controller code combined.");
    }

    /**
     * @covers \swl\core\Lexer::run
     * @covers \swl\core\Token::__construct
     * @covers \swl\core\Lexer::analize
     * @covers \swl\core\Token::test
     * @covers \swl\core\Tokens::GetPattern
     * @covers \swl\core\Lexer::pairChar
     * @covers \swl\core\Lexer::match
     * @covers \swl\core\LexerCombinations::GetTokens
     * @covers \swl\core\LexerCombinations::GetTokensWithoutWhitespaces
     */
    public function testSWLControllerFileAndCountTokens()
    {
        $from = $this->lexFileInstances('controller.swl');

        $arr = $from->ToArray();

        $this->assertCount(386, $arr, "Test a SWL file Controller code.");

        $comb = new LexerCombinations(new Lexer(\INCPATH . 'controller.swl'));

        $tokens  = $comb->getTokens();
        $ntokens = $comb->getTokensWithoutWhitespaces();

        $this->assertInstanceOf(\Generator::class, $tokens,
                                'Test if Lexer Combinations return a Generator instance.');

        $this->assertInstanceOf(\Generator::class, $ntokens,
                                'Test if Lexer Combinations return a Generator instance.');

        $linq  = new Linq($tokens);
        $nlinq = new Linq($ntokens);

        $arr  = $linq->ToArray();
        $narr = $nlinq->ToArray();

        $this->assertCount(376, $arr,
                           "Test a SWL file Controller code combinated.");

        $this->assertCount(165, $narr,
                           "Test a SWL file Controller code combinated.");
    }

}
